<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect('/');
    }

    /**
     * Logouts the admin
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logoutAdmin()
    {
        Auth::guard('admin')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect('/');
    }

    /**
     * Logouts the admin
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logoutOwner()
    {
        Auth::guard('owner')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect('/');
    }
}
