<?php

/**
 * Check if user is logged in
 * 
 * @return bool
 */
function is_logged_in()
{
    return session()->get('logged_in') === true;
}

/**
 * Get current user info
 * 
 * @return array|null
 */
function current_user()
{
    if (!is_logged_in()) {
        return null;
    }
    
    return [
        'id' => session()->get('user_id'),
        'username' => session()->get('username'),
        'email' => session()->get('email')
    ];
}

/**
 * Format date for display
 * 
 * @param string $date
 * @return string
 */
function format_date($date)
{
    return date('F j, Y, g:i a', strtotime($date));
}