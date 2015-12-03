<?php

namespace Bfs;

/**
 * 
 *
 * @author William Moffitt
 */
class Crypto {
    
    public static function hashPassword($password) {
        if (!(isset($password))) {
            return false;
        }
        
        return password_hash($password, PASSWORD_BCRYPT);
    }
    
}
