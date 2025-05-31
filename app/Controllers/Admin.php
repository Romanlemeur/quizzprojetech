<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\QuizModel;
use App\Models\QuestionModel;
use App\Models\OptionModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    protected $categoryModel;
    protected $quizModel;
    protected $questionModel;
    protected $optionModel;
    protected $userModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->quizModel = new QuizModel();
        $this->questionModel = new QuestionModel();
        $this->optionModel = new OptionModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Tableau de bord administrateur',
            'total_quizzes' => $this->quizModel->countAll(),
            'total_categories' => $this->categoryModel->countAll(),
            'total_users' => $this->userModel->countAll(),
            'recent_quizzes' => $this->quizModel->orderBy('created_at', 'DESC')->limit(5)->find()
        ];

        return view('templates/header', $data)
            . view('admin/dashboard')
            . view('templates/footer');
    }

    public function manageQuizzes()
    {
        $data = [
            'title' => 'Gestion des Quiz',
            'quizzes' => $this->quizModel->findAll()
        ];

        return view('templates/header', $data)
            . view('admin/quizzes')
            . view('templates/footer');
    }

    public function createQuiz()
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'category_id' => $this->request->getPost('category_id'),
                'status' => 'draft',
                'time_limit' => $this->request->getPost('time_limit'),
                'passing_percentage' => $this->request->getPost('passing_percentage'),
                'max_attempts' => $this->request->getPost('max_attempts')
            ];

            if ($this->quizModel->insert($data)) {
                return redirect()->to('/admin/manageQuizzes')->with('success', 'Quiz créé avec succès');
            }
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du quiz');
        }

        $data = [
            'title' => 'Créer un nouveau Quiz',
            'categories' => $this->categoryModel->findAll()
        ];

        return view('templates/header', $data)
            . view('admin/create_quiz')
            . view('templates/footer');
    }

    public function editQuiz($id)
    {
        $quiz = $this->quizModel->find($id);
        
        if (!$quiz) {
            return redirect()->to('/admin/manageQuizzes')->with('error', 'Quiz non trouvé');
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'category_id' => $this->request->getPost('category_id'),
                'status' => $this->request->getPost('status'),
                'time_limit' => $this->request->getPost('time_limit'),
                'passing_percentage' => $this->request->getPost('passing_percentage'),
                'max_attempts' => $this->request->getPost('max_attempts')
            ];

            if ($this->quizModel->update($id, $data)) {
                return redirect()->to('/admin/manageQuizzes')->with('success', 'Quiz mis à jour avec succès');
            }
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour du quiz');
        }

        $data = [
            'title' => 'Modifier le Quiz',
            'quiz' => $quiz,
            'categories' => $this->categoryModel->findAll()
        ];

        return view('templates/header', $data)
            . view('admin/edit_quiz')
            . view('templates/footer');
    }

    public function deleteQuiz($id)
    {
        if ($this->quizModel->delete($id)) {
            return redirect()->to('/admin/manageQuizzes')->with('success', 'Quiz supprimé avec succès');
        }
        return redirect()->back()->with('error', 'Erreur lors de la suppression du quiz');
    }

    public function manageCategories()
    {
        $data = [
            'title' => 'Gestion des Catégories',
            'categories' => $this->categoryModel->findAll()
        ];

        return view('templates/header', $data)
            . view('admin/categories')
            . view('templates/footer');
    }
} 