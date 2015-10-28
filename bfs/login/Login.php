<?php

namespace Bfs\Login;

use Bfs\Crypto;
use Bfs\ErrorCodes;
use Bfs\Database\Connection;
use Bfs\Database\User;
use Bfs\Database\Dao\UserDao;

/**
 * 
 *
 * @author William Moffitt
 */
class Login {
    
    public function login(UserDao $userDao) {
        if (!isset($userDao->password)) {
            return array(
                'error' => true,
                'code'  => ErrorCodes::USER_INCOMPLETE,
                'msg'   => "Missing information"
            );
        }
        
        $conn = new Connection();
        $user = new User($conn->getPdo());
        $result = $user->read($userDao);
        
        if (!($result instanceof UserDao)) {
            return $result;
        }
        
        $hashedPassword = Crypto::hashPassword($userDao->password, $result->salt);
        if ($result->password == $hashedPassword) {
            return array(
                'error'        => false,
                'id'           => $result->id,
                'first_name'   => $result->first_name,
                'last_name'    => $result->last_name,
                'username'     => $result->username,
                'dob'          => $result->dob,
                'email'        => $result->email,
                'is_moderator' => $result->is_moderator
            );
        } else {
            return array(
                'error' => true,
                'code'  => ErrorCodes::PASSWORD_INVALID,
                'msg'   => "Password invalid"
            );
        }
    }
    
}
