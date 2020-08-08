<?php

namespace App;

use App\security\ForbiddenException;

class Auth
{

    public static function check()
    {
        if (!isset($_SESSION['auth'])) {
            throw new ForbiddenException();
        }

        if (!session_status()) {
            session_start();
            $_SESSION['id'] = 1;
        } else {
            header('Location :/');
        }
    }
}