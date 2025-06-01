<?php

// Pour vérifier si l'utilisateur est connecté
if (!function_exists('is_logged_in')) {
    function is_logged_in()
    {
        return session()->has('user_id') && session()->has('logged_in');
    }
}

// Récupère les infos de l'utilisateur connecté
if (!function_exists('current_user')) {
    function current_user()
    {
        if (!is_logged_in()) {
            return null;
        }
        
        // TODO: mettre en cache pour ne pas appeler la BDD à chaque fois?
        $userModel = new \App\Models\UserModel();
        return $userModel->find(session()->get('user_id'));
    }
}

// Formater les dates pour l'affichage
function format_date($date)
{
    // Format américain pour l'instant, changer plus tard
    return date('F j, Y, g:i a', strtotime($date));
}

// Check si l'utilisateur est admin
if (!function_exists('is_admin')) {
    function is_admin()
    {
        return is_logged_in() && session()->get('role') === 'admin';
    }
}

// Empêche l'accès aux non-admins
if (!function_exists('ensure_admin')) {
    function ensure_admin()
    {
        if (!is_admin()) {
            session()->setFlashdata('error', 'Accès restreint aux administrateurs');
            return redirect()->to(base_url());
        }
    }
}