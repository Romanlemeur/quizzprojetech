<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\QuizModel;
use App\Models\QuestionModel;
use App\Models\OptionModel;
use App\Models\ScoreModel;

class Quiz extends BaseController
{
    protected $categoryModel;
    protected $quizModel;
    protected $questionModel;
    protected $optionModel;
    protected $scoreModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->quizModel = new QuizModel();
        $this->questionModel = new QuestionModel();
        $this->optionModel = new OptionModel();
        $this->scoreModel = new ScoreModel();
    }

    public function index()
    {
        // Check if user is logged in
        if (!is_logged_in()) {
            return redirect()->to('login?redirect=quiz');
        }

        $data = [
            'title' => 'Quiz disponibles',
            'categories' => $this->categoryModel->findAll(),
            'is_admin' => session()->get('role') === 'admin'
        ];

        return view('templates/header', $data)
            . view('quiz/categories')
            . view('templates/footer');
    }

    public function category($categoryId)
    {
        // Check if user is logged in
        if (!is_logged_in()) {
            return redirect()->to('login?redirect=quiz');
        }

        $category = $this->categoryModel->find($categoryId);

        if (!$category) {
            return redirect()->to('quiz');
        }

        $data = [
            'title' => $category['name'] . ' - Quiz',
            'category' => $category,
            'quizzes' => $this->quizModel->where('category_id', $categoryId)
                                       ->where('status', 'active')
                                       ->findAll(),
            'is_admin' => session()->get('role') === 'admin'
        ];

        return view('templates/header', $data)
            . view('quiz/quizzes')
            . view('templates/footer');
    }

    public function start($quizId)
    {
        // Check if user is logged in
        if (!is_logged_in()) {
            return redirect()->to('login?redirect=quiz');
        }

        $quiz = $this->quizModel->find($quizId);

        if (!$quiz || $quiz['status'] !== 'active') {
            return redirect()->to('quiz')->with('error', 'Ce quiz n\'est pas disponible.');
        }

        // Vérifier si l'utilisateur a dépassé le nombre maximum de tentatives
        if ($quiz['max_attempts']) {
            $attempts = $this->scoreModel->where('user_id', session()->get('user_id'))
                                       ->where('quiz_id', $quizId)
                                       ->countAllResults();
            
            if ($attempts >= $quiz['max_attempts']) {
                return redirect()->to('quiz')->with('error', 'Vous avez atteint le nombre maximum de tentatives pour ce quiz.');
            }
        }

        $questions = $this->questionModel->where('quiz_id', $quizId)
                                       ->orderBy('question_order', 'ASC')
                                       ->findAll();
        
        foreach ($questions as &$question) {
            $question['options'] = $this->optionModel->where('question_id', $question['id'])
                                                    ->orderBy('option_order', 'ASC')
                                                    ->findAll();
        }

        $data = [
            'title' => $quiz['title'],
            'quiz' => $quiz,
            'questions' => $questions,
            'totalQuestions' => count($questions)
        ];

        return view('templates/header', $data)
            . view('quiz/take_quiz')
            . view('templates/footer');
    }

    public function submit()
    {
        if (!is_logged_in()) {
            return redirect()->to('login');
        }

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('quiz');
        }

        $quizId = $this->request->getPost('quiz_id');
        $quiz = $this->quizModel->find($quizId);

        if (!$quiz || $quiz['status'] !== 'active') {
            return redirect()->to('quiz')->with('error', 'Ce quiz n\'est pas disponible.');
        }

        $answers = [];
        foreach ($this->request->getPost() as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $questionId = substr($key, strlen('question_'));
                $answers[$questionId] = $value;
            }
        }

        $score = 0;
        $correctAnswers = 0;
        $totalQuestions = 0;

        $questions = $this->questionModel->where('quiz_id', $quizId)->findAll();
        $totalQuestions = count($questions);

        foreach ($questions as $question) {
            $questionId = $question['id'];
            
            if (isset($answers[$questionId])) {
                $selectedOptionId = $answers[$questionId];
                
                $option = $this->optionModel->where('id', $selectedOptionId)
                                          ->where('question_id', $questionId)
                                          ->first();
                
                if ($option && $option['is_correct'] == 1) {
                    $score += $question['points'];
                    $correctAnswers++;
                }
            }
        }

        $percentage = ($totalQuestions > 0) ? round(($correctAnswers / $totalQuestions) * 100) : 0;
        $passed = $percentage >= $quiz['passing_percentage'];

        $scoreData = [
            'user_id' => session()->get('user_id'),
            'quiz_id' => $quizId,
            'score' => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'percentage' => $percentage,
            'passed' => $passed,
            'completed_at' => date('Y-m-d H:i:s')
        ];
        
        $this->scoreModel->insert($scoreData);

        return redirect()->to("quiz/result/$quizId/$score/$correctAnswers/$totalQuestions");
    }

    public function result($quizId, $score, $correct, $total)
    {
        if (!is_logged_in()) {
            return redirect()->to('login');
        }

        $quiz = $this->quizModel->find($quizId);

        if (!$quiz) {
            return redirect()->to('quiz');
        }

        $percentage = ($total > 0) ? round(($correct / $total) * 100) : 0;
        $passed = $percentage >= $quiz['passing_percentage'];

        $message = '';
        if ($percentage >= 90) {
            $message = 'Excellent ! Vous maîtrisez parfaitement ce sujet !';
        } elseif ($percentage >= 70) {
            $message = 'Très bien ! Vous avez une bonne compréhension du sujet !';
        } elseif ($percentage >= $quiz['passing_percentage']) {
            $message = 'Bien ! Vous avez atteint le score minimum requis.';
        } else {
            $message = 'Continuez à pratiquer ! Ce sujet nécessite plus de révision.';
        }

        $data = [
            'title' => 'Résultats du Quiz',
            'quiz' => $quiz,
            'score' => $score,
            'correct' => $correct,
            'total' => $total,
            'percentage' => $percentage,
            'passed' => $passed,
            'message' => $message
        ];

        return view('templates/header', $data)
            . view('quiz/result')
            . view('templates/footer');
    }
} 