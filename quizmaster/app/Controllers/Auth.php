<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ScoreModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $scoreModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->scoreModel = new ScoreModel();
    }

    public function login()
    {
        // If already logged in, redirect to home
        if (is_logged_in()) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Login',
            'validation' => \Config\Services::validation(),
            'redirect' => $this->request->getGet('redirect') ?? ''
        ];

        return view('templates/header', $data)
            . view('auth/login')
            . view('templates/footer');
    }

    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $redirect = $this->request->getPost('redirect') ?? '';

        // Check user
        $user = $this->userModel->where('email', $email)->first();
        
        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid login credentials');
        }

        // Set session data
        $userData = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'logged_in' => true
        ];

        $this->session->set($userData);
        
        // Redirect based on "redirect" parameter or default to home
        if (!empty($redirect)) {
            return redirect()->to($redirect);
        }
        
        return redirect()->to('/');
    }

    public function register()
    {
        // If already logged in, redirect to home
        if (is_logged_in()) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Register',
            'validation' => \Config\Services::validation()
        ];

        return view('templates/header', $data)
            . view('auth/register')
            . view('templates/footer');
    }

    public function attemptRegister()
    {
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ];

        $this->userModel->insert($data);
        
        return redirect()->to('login')->with('message', 'Registration successful. You can now login.');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'history' => $this->scoreModel->getUserQuizHistory($userId)
        ];

        return view('templates/header', $data)
            . view('auth/profile')
            . view('templates/footer');
    }
}