<?php

namespace App\Libraries;

class EncryptLib
{
    public function makePassword($password): string
    {
        $password = $password ?? false;
        if (!$password) {
            return '';
        }
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public function checkPassword($post, $password): bool
    {
        if (isset($post) && isset($password)) {
            if (password_verify($post, $password)) {
                return true;
            }
        }
        return false;
    }

    public function getSha1($val): string
    {
        if(!$val){
            return '';
        }
        return sha1($val);
    }
}
