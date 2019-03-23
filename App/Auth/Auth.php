<?php

namespace App\Auth;

use App\Models\User;

class Auth {
    public function loginAttempt($username, $password) {
        $user = User::where('username', $username)->first();

        if(!$user) {
            return false;
        }

        if(!password_verify($password, $user->password_hash)) {
            return false;
        } else {
            $_SESSION['user'] = $user->id;
        }

        return true;
    }

    public function logout() {
        if(isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            return true;
        }
        return false;
    }

};