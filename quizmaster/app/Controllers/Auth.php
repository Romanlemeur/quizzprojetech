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
        // Si déjà connecté, redirection
        if (is_logged_in()) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Login',
            'validation' => \Config\Services::validation(),
            'redirect' => $this->request->getGet('redirect') ?? '',
            'error' => session()->getFlashdata('error') // Pour afficher les erreurs de connexion
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
            return redirect()->back()->withInput()->with('error', 'Veuillez remplir tous les champs correctement');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $redirect = $this->request->getPost('redirect') ?? '';

        // Vérif de l'utilisateur
        $user = $this->userModel->where('email', $email)->first();
        
        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email ou mot de passe incorrect');
        }

        // TODO: ajouter option "se souvenir de moi"?
        
        // On met les infos en session
        $user_data = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'] ?? 'user',
            'logged_in' => true
        ];

        $this->session->set($user_data);
        
        // Redirection
        if (!empty($redirect)) {
            return redirect()->to($redirect);
        }
        
        if ($user_data['role'] === 'admin') {
            return redirect()->to('admin/dashboard');
        }
        
        return redirect()->to('/');
    }

    public function register()
    {
        // Si déjà connecté, redirection
        if (is_logged_in()) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Register',
            'validation' => \Config\Services::validation(),
            'error' => session()->getFlashdata('error'),
            'success' => session()->getFlashdata('success')
        ];

        return view('templates/header', $data)
            . view('auth/register')
            . view('templates/footer');
    }

    public function attemptRegister()
    {
        $rules = [
            'username' => [
                'rules' => 'required|min_length[3]|is_unique[users.username]',
                'errors' => [
                    'is_unique' => 'Ce nom d\'utilisateur est déjà pris'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'is_unique' => 'Cet email est déjà utilisé'
                ]
            ],
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            // Pour capturer l'erreur spécifique
            $errors = $this->validator->getErrors();
            $errorMessage = reset($errors); // Prend la première erreur
            
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'user', // Tous les nouveaux sont des users normaux
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->userModel->insert($data);
        
        return redirect()->to('login')->with('message', 'Inscription réussie! Vous pouvez maintenant vous connecter.');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }

    public function profile()
    {
        if (!is_logged_in()) {
            return redirect()->to('login?redirect=profile');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Mon Profil',
            'user' => $user,
            'history' => $this->userModel->getUserQuizHistory($userId)
        ];

        return view('templates/header', $data)
            . view('auth/profile')
            . view('templates/footer');
    }
    
    // Pour ajouter un admin (uniquement accessible aux admins)
    public function addAdmin()
    {
        if (!is_logged_in() || $this->session->get('role') !== 'admin') {
            return redirect()->to('/');
        }
        
        if ($this->request->getMethod() !== 'post') {
            $data = [
                'title' => 'Ajouter un administrateur',
                'validation' => \Config\Services::validation(),
            ];
            
            return view('templates/admin_header', $data)
                . view('admin/add_admin')
                . view('templates/admin_footer');
        }
        
        // Validation 
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Création admin
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'admin'
        ];
        
        $this->userModel->insert($data);
        
        return redirect()->to('admin/users')->with('message', 'Administrateur ajouté avec succès');
    }
}