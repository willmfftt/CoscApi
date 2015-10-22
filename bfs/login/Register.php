<?php

namespace Bfs\Login;

use Bfs\Database\Connection;
use Bfs\Database\User;
use Bfs\Database\Dao\UserDao;

/**
 * 
 *
 * @author William Moffitt
 */
class Register {
    
    public function register(UserDao $userDao) {
        $conn = new Connection();
        $user = new User($conn->getPdo());
        
        return $user->create($userDao);
    }
    
}
