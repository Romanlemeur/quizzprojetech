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
            'title' => 'Quiz Categories',
            'categories' => $this->categoryModel->findAll()
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
            'title' => $category['name'] . ' Quizzes',
            'category' => $category,
            'quizzes' => $this->quizModel->where('category_id', $categoryId)->findAll()
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

        if (!$quiz) {
            return redirect()->to('quiz');
        }

        $questions = $this->questionModel->where('quiz_id', $quizId)->orderBy('question_order', 'ASC')->findAll();
        
        foreach ($questions as &$question) {
            $question['options'] = $this->optionModel->where('question_id', $question['id'])->orderBy('option_order', 'ASC')->findAll();
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
        // Check if user is logged in
        if (!is_logged_in()) {
            return redirect()->to('login?redirect=quiz');
        }

        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('quiz');
        }

        $quizId = $this->request->getPost('quiz_id');
        $answers = [];
        
        // Get answers from form
        foreach ($this->request->getPost() as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $questionId = substr($key, strlen('question_'));
                $answers[$questionId] = $value;
            }
        }

        // Calculate score
        $score = 0;
        $correctAnswers = 0;
        $totalQuestions = 0;

        $questions = $this->questionModel->where('quiz_id', $quizId)->findAll();
        $totalQuestions = count($questions);

        foreach ($questions as $question) {
            $questionId = $question['id'];
            
            if (isset($answers[$questionId])) {
                $selectedOptionId = $answers[$questionId];
                
                // Check if selected option is correct
                $option = $this->optionModel->where('id', $selectedOptionId)->where('question_id', $questionId)->first();
                
                if ($option && $option['is_correct'] == 1) {
                    $score += $question['points'];
                    $correctAnswers++;
                }
            }
        }

        $percentage = ($totalQuestions > 0) ? round(($correctAnswers / $totalQuestions) * 100) : 0;

        // Save score to database
        $userId = session()->get('user_id');
        $scoreData = [
            'user_id' => $userId,
            'quiz_id' => $quizId,
            'score' => $score,
            'completed_at' => date('Y-m-d H:i:s')
        ];
        
        $this->scoreModel->insert($scoreData);

        // Redirect to results page
        return redirect()->to("quiz/result/$quizId/$score/$correctAnswers/$totalQuestions");
    }

    public function result($quizId, $score, $correct, $total)
    {
        // Check if user is logged in
        if (!is_logged_in()) {
            return redirect()->to('login?redirect=quiz');
        }

        $quiz = $this->quizModel->find($quizId);

        if (!$quiz) {
            return redirect()->to('quiz');
        }

        $percentage = ($total > 0) ? round(($correct / $total) * 100) : 0;

        // Determine performance message
        $message = '';
        if ($percentage >= 90) {
            $message = 'Excellent! You\'re a master of this topic!';
        } elseif ($percentage >= 70) {
            $message = 'Great job! You have a solid understanding of this subject!';
        } elseif ($percentage >= 50) {
            $message = 'Good effort! You know the basics, but there\'s room for improvement.';
        } else {
            $message = 'Keep practicing! This topic needs a bit more study.';
        }

        $data = [
            'title' => 'Quiz Results',
            'quiz' => $quiz,
            'score' => $score,
            'correct' => $correct,
            'total' => $total,
            'percentage' => $percentage,
            'message' => $message
        ];

        return view('templates/header', $data)
            . view('quiz/result')
            . view('templates/footer');
    }
}