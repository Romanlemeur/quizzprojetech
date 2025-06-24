<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UtilisateurModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected $utilisateurModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
    }

    public function index()
    {
        // Rediriger vers la page de connexion
        return redirect()->to('/auth/login');
    }

    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = $this->utilisateurModel->authenticate($email, $password);

            if ($user) {
                // Créer la session
                $session = session();
                $session->set([
                    'idUtilisateur' => $user['idUtilisateur'],
                    'email' => $user['email'],
                    'pseudo' => $user['pseudo'],
                    'role' => $user['role'],
                    'logged_in' => true
                ]);

                // Rediriger selon le rôle
                if ($user['role'] === 'admin') {
                    return redirect()->to('/admin/dashboard');
                } else {
                    return redirect()->to('/joueur/dashboard');
                }
            } else {
                return redirect()->back()->with('error', 'Email ou mot de passe incorrect');
            }
        }

        return view('auth/login');
    }

    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'email' => $this->request->getPost('email'),
                'motDePasse' => $this->request->getPost('password'),
                'pseudo' => $this->request->getPost('pseudo'),
                'role' => 'joueur' // Par défaut, les nouveaux utilisateurs sont des joueurs
            ];

            if ($this->utilisateurModel->save($data)) {
                return redirect()->to('/auth/login')->with('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->utilisateurModel->errors());
            }
        }

        return view('auth/register');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        
        return redirect()->to('/auth/login')->with('success', 'Vous avez été déconnecté avec succès.');
    }

    public function checkAuth()
    {
        $session = session();
        
        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        return true;
    }

    public function requireAdmin()
    {
        $this->checkAuth();
        
        $session = session();
        if ($session->get('role') !== 'admin') {
            return redirect()->to('/joueur/dashboard')->with('error', 'Accès non autorisé');
        }
        
        return true;
    }

    public function requireJoueur()
    {
        $this->checkAuth();
        
        $session = session();
        if ($session->get('role') !== 'joueur') {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès non autorisé');
        }
        
        return true;
    }
}
