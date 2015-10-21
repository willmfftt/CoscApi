<?php

namespace Bfs;

/**
 * 
 *
 * @author William Moffitt
 */
class Crypto {
    
    public static function hashPassword($password, $salt) {
        if (!(isset($password) || isset($salt))) {
            return false;
        }
        
        return hash_hmac('sha256', $password, $salt);
    }
    
    public static function generateSalt() {
        return md5(uniqid());
    }
    
}
