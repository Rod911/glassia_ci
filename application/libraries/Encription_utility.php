<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Encription_utility {
    /**
     * Used to generate the Salt hashing password.
     * @param string password
     * @return: salt hashed password
     */
    public function getSaltPassword($password) {
        $password = $password;
        $password .= "Za87Nu2pIL";
        $salt = sha1(md5($password));
        $password = md5($password . $salt);
        return $password;
    }
}
