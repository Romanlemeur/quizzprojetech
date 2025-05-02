-- Database structure for Quiz Application

-- Drop existing tables if they exist
DROP TABLE IF EXISTS user_scores;
DROP TABLE IF EXISTS options;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS quizzes;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255)
);

-- Create quizzes table
CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    time_limit INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Create questions table
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    question_order INT DEFAULT 0,
    points INT DEFAULT 1,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Create options table
CREATE TABLE options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    option_text TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    option_order INT DEFAULT 0,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- Create user_scores table
CREATE TABLE user_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quiz_id INT NOT NULL,
    score INT NOT NULL,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Insert sample data for categories
INSERT INTO categories (name, description, image) VALUES
('General Knowledge', 'Test your knowledge on various general topics', 'general.jpg'),
('Science', 'Questions about physics, chemistry, biology and more', 'science.jpg'),
('History', 'Explore the past with these historical questions', 'history.jpg'),
('Geography', 'Test your knowledge of the world we live in', 'geography.jpg'),
('Entertainment', 'Questions about movies, music, TV shows and more', 'entertainment.jpg');

-- Insert sample data for quizzes
INSERT INTO quizzes (category_id, title, description, time_limit) VALUES
(1, 'Basic General Knowledge', 'A quiz about basic general knowledge topics', 300),
(2, 'Basic Science Quiz', 'Test your knowledge of basic scientific facts', 300),
(3, 'World History Basics', 'A quiz covering major world history events', 300),
(4, 'World Capitals', 'Test your knowledge of world capitals', 300),
(5, 'Popular Movies', 'A quiz about popular movies from different eras', 300);

-- Insert sample questions for General Knowledge quiz
INSERT INTO questions (quiz_id, question_text, question_order, points) VALUES
(1, 'What is the capital of France?', 1, 1),
(1, 'Which planet is known as the Red Planet?', 2, 1),
(1, 'Who painted the Mona Lisa?', 3, 1),
(1, 'What is the largest ocean on Earth?', 4, 1),
(1, 'How many sides does a hexagon have?', 5, 1);

-- Insert options for General Knowledge questions
INSERT INTO options (question_id, option_text, is_correct, option_order) VALUES
(1, 'Paris', TRUE, 1),
(1, 'London', FALSE, 2),
(1, 'Berlin', FALSE, 3),
(1, 'Rome', FALSE, 4),
(2, 'Venus', FALSE, 1),
(2, 'Mars', TRUE, 2),
(2, 'Jupiter', FALSE, 3),
(2, 'Saturn', FALSE, 4),
(3, 'Vincent van Gogh', FALSE, 1),
(3, 'Leonardo da Vinci', TRUE, 2),
(3, 'Pablo Picasso', FALSE, 3),
(3, 'Michelangelo', FALSE, 4),
(4, 'Atlantic Ocean', FALSE, 1),
(4, 'Indian Ocean', FALSE, 2),
(4, 'Arctic Ocean', FALSE, 3),
(4, 'Pacific Ocean', TRUE, 4),
(5, 'Four', FALSE, 1),
(5, 'Five', FALSE, 2),
(5, 'Six', TRUE, 3),
(5, 'Eight', FALSE, 4);

-- Insert sample questions for Science quiz
INSERT INTO questions (quiz_id, question_text, question_order, points) VALUES
(2, 'What is the chemical symbol for water?', 1, 1),
(2, 'What is the hardest natural substance on Earth?', 2, 1),
(2, 'What is the nearest planet to the Sun?', 3, 1),
(2, 'What is the process by which plants make their own food?', 4, 1),
(2, 'What is the unit of electric current?', 5, 1);

-- Insert options for Science questions
INSERT INTO options (question_id, option_text, is_correct, option_order) VALUES
(6, 'H2O', TRUE, 1),
(6, 'CO2', FALSE, 2),
(6, 'O2', FALSE, 3),
(6, 'H2SO4', FALSE, 4),
(7, 'Gold', FALSE, 1),
(7, 'Diamond', TRUE, 2),
(7, 'Platinum', FALSE, 3),
(7, 'Iron', FALSE, 4),
(8, 'Venus', FALSE, 1),
(8, 'Mercury', TRUE, 2),
(8, 'Earth', FALSE, 3),
(8, 'Mars', FALSE, 4),
(9, 'Respiration', FALSE, 1),
(9, 'Photosynthesis', TRUE, 2),
(9, 'Digestion', FALSE, 3),
(9, 'Transpiration', FALSE, 4),
(10, 'Volt', FALSE, 1),
(10, 'Watt', FALSE, 2),
(10, 'Ampere', TRUE, 3),
(10, 'Ohm', FALSE, 4);