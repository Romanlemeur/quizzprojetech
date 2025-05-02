<?php
// General helper functions

/**
 * Sanitize user input
 * @param string $data
 * @return string
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Check if a username already exists
 * @param string $username
 * @return bool
 */
function usernameExists($username) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

/**
 * Check if an email already exists
 * @param string $email
 * @return bool
 */
function emailExists($email) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

/**
 * Register a new user
 * @param string $username
 * @param string $email
 * @param string $password
 * @return array
 */
function registerUser($username, $email, $password) {
    global $conn;
    
    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'All fields are required'];
    }
    
    if (usernameExists($username)) {
        return ['success' => false, 'message' => 'Username already exists'];
    }
    
    if (emailExists($email)) {
        return ['success' => false, 'message' => 'Email already exists'];
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Registration successful', 'user_id' => $conn->insert_id];
    } else {
        return ['success' => false, 'message' => 'Registration failed: ' . $conn->error];
    }
}

/**
 * Login a user
 * @param string $email
 * @param string $password
 * @return array
 */
function loginUser($email, $password) {
    global $conn;
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Email and password are required'];
    }
    
    // Get user
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            return ['success' => true, 'message' => 'Login successful', 'user_id' => $user['id']];
        } else {
            return ['success' => false, 'message' => 'Invalid password'];
        }
    } else {
        return ['success' => false, 'message' => 'User not found'];
    }
}

/**
 * Get all quiz categories
 * @return array
 */
function getQuizCategories() {
    global $conn;
    
    $sql = "SELECT * FROM categories ORDER BY name ASC";
    $result = $conn->query($sql);
    
    $categories = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
    
    return $categories;
}

/**
 * Get quizzes by category
 * @param int $category_id
 * @return array
 */
function getQuizzesByCategory($category_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM quizzes WHERE category_id = ? ORDER BY title ASC");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $quizzes = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $quizzes[] = $row;
        }
    }
    
    return $quizzes;
}

/**
 * Get a specific quiz by ID
 * @param int $quiz_id
 * @return array|null
 */
function getQuizById($quiz_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM quizzes WHERE id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    
    return null;
}

/**
 * Get quiz questions by quiz ID
 * @param int $quiz_id
 * @return array
 */
function getQuizQuestions($quiz_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY question_order ASC");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $questions = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }
    }
    
    return $questions;
}

/**
 * Get options for a question
 * @param int $question_id
 * @return array
 */
function getQuestionOptions($question_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM options WHERE question_id = ? ORDER BY option_order ASC");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $options = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $options[] = $row;
        }
    }
    
    return $options;
}

/**
 * Calculate quiz score
 * @param int $quiz_id
 * @param array $answers (question_id => option_id)
 * @return array
 */
function calculateQuizScore($quiz_id, $answers) {
    global $conn;
    
    $score = 0;
    $total_questions = 0;
    $correct_answers = 0;
    
    $questions = getQuizQuestions($quiz_id);
    $total_questions = count($questions);
    
    foreach ($questions as $question) {
        $question_id = $question['id'];
        
        if (isset($answers[$question_id])) {
            $selected_option_id = $answers[$question_id];
            
            // Check if the selected option is correct
            $stmt = $conn->prepare("SELECT is_correct FROM options WHERE id = ? AND question_id = ?");
            $stmt->bind_param("ii", $selected_option_id, $question_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $option = $result->fetch_assoc();
                if ($option['is_correct'] == 1) {
                    $score += $question['points'];
                    $correct_answers++;
                }
            }
        }
    }
    
    return [
        'score' => $score,
        'total_questions' => $total_questions,
        'correct_answers' => $correct_answers,
        'percentage' => ($total_questions > 0) ? round(($correct_answers / $total_questions) * 100) : 0
    ];
}

/**
 * Save quiz score
 * @param int $user_id
 * @param int $quiz_id
 * @param int $score
 * @return bool
 */
function saveQuizScore($user_id, $quiz_id, $score) {
    global $conn;
    
    $stmt = $conn->prepare("
        INSERT INTO user_scores (user_id, quiz_id, score, completed_at) 
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->bind_param("iii", $user_id, $quiz_id, $score);
    
    return $stmt->execute();
}

/**
 * Get leaderboard data
 * @param int $quiz_id (optional)
 * @param int $limit (optional)
 * @return array
 */
function getLeaderboard($quiz_id = null, $limit = 10) {
    global $conn;
    
    $sql = "
        SELECT u.username, us.score, us.completed_at, q.title as quiz_title, q.id as quiz_id
        FROM user_scores us
        JOIN users u ON us.user_id = u.id
        JOIN quizzes q ON us.quiz_id = q.id
    ";
    
    $params = [];
    $types = "";
    
    if ($quiz_id) {
        $sql .= " WHERE us.quiz_id = ?";
        $params[] = $quiz_id;
        $types .= "i";
    }
    
    $sql .= " ORDER BY us.score DESC, us.completed_at ASC LIMIT ?";
    $params[] = $limit;
    $types .= "i";
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $leaderboard = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $leaderboard[] = $row;
        }
    }
    
    return $leaderboard;
}

/**
 * Get user's quiz history
 * @param int $user_id
 * @return array
 */
function getUserQuizHistory($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT us.*, q.title as quiz_title
        FROM user_scores us
        JOIN quizzes q ON us.quiz_id = q.id
        WHERE us.user_id = ?
        ORDER BY us.completed_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $history = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $history[] = $row;
        }
    }
    
    return $history;
}

/**
 * Format date for display
 * @param string $date
 * @return string
 */
function formatDate($date) {
    return date('F j, Y, g:i a', strtotime($date));
}