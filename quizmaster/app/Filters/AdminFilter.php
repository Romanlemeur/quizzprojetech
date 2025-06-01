<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Si l'utilisateur n'est pas connecté ou n'est pas admin, rediriger vers la page d'accueil
        if (!is_admin()) {
            return redirect()->to('/')->with('error', 'Accès restreint aux administrateurs');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après l'exécution
    }
} 