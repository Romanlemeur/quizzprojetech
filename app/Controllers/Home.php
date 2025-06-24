<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $session = session();
        
        // Si l'utilisateur est connecté, le rediriger vers son dashboard
        if ($session->get('logged_in')) {
            if ($session->get('role') === 'admin') {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/joueur/dashboard');
            }
        }
        
        // Sinon, rediriger vers la page de connexion
        return redirect()->to('/auth/login');
    }

    public function about()
    {
        return view('home/about');
    }

    public function contact()
    {
        return view('home/contact');
    }
}
