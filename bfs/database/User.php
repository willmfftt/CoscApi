<?php

namespace Bfs\Database;

use PDO;
use PDOException;
use Bfs\Database\Dao\UserDao;
use Bfs\Crypto;
use Bfs\ErrorCodes;
use Zend\Log\Writer\Syslog;

/**
 * 
 *
 * @author William Moffitt
 */
class User {
    
    private $dbh;
    
    public function __construct(PDO $dbh) {
        $this->dbh = $dbh;
    }
    
    public function create(UserDao $user) {
        if (!self::hasRequired($user)) {
            return array(
                'error' => true,
                'code'  => ErrorCodes::USER_INCOMPLETE,
                'msg'   => "Cannot create user, missing user information"
            );
        }
        
        /* Check for existing user */
        if ($this->userExists($user)) {
            return $this->error(ErrorCodes::USERNAME_INVALID
                    , "Username already exists");
        }
        
        try {
            $sql = "INSERT INTO " . USERTABLE . " (first_name, last_name, dob, email, is_moderator, date_start, modify_date)" 
                    . " VALUES (:first_name, :last_name, :dob, :email, :is_moderator, now(), now())";
            $stmt = $this->dbh->prepare($sql);
            $result = $stmt->execute(array(
                ':first_name'   => $user->first_name,
                ':last_name'    => $user->last_name,
                ':dob'          => $user->dob,
                ':email'        => $user->email,
                ':is_moderator' => 0
            ));
            $stmt->closeCursor(); 
            
            if (!$result) {
                return $this->error(ErrorCodes::USER_GENERIC_ERROR
                    , "Failed to create user");
            }
            
            $user->id = $this->dbh->lastInsertId();
            $user->salt = Crypto::generateSalt();
            $user->password = Crypto::hashPassword($user->password, $user->salt);
            
            $sql = "INSERT INTO " . USERCREDTABLE . " (user_id, username, password, salt)"
                    . " VALUES (:user_id, :username, :password, :salt)";
            $stmt = $this->dbh->prepare($sql);            
            $result = $stmt->execute(array(
                ':user_id'  => $user->id,
                ':username' => $user->username,
                ':password' => $user->password,
                ':salt'     => $user->salt
            ));
            $stmt->closeCursor();
            
            if (!$result) {
                return $this->error(ErrorCodes::USER_GENERIC_ERROR
                    , "Failed to create user");
            }
            
            return array(
                'error' => false,
                'id'    => $user->id
            );
        } catch (PDOException $e) {
            $syslog = new Syslog();
            $syslog->write($e);
            $syslog->shutdown();
            
            return $this->error(ErrorCodes::USER_GENERIC_ERROR
                    , "Failed to create user");
        }
    }
    
    public function read(UserDao $user) {
        if (!isset($user->username) && count($user->username) == 0) {
            return $this->error(ErrorCodes::USER_INCOMPLETE
                    , "Not enough information provided");
        }
        
        try {
            $sql = "SELECT * FROM " . USERTABLE . " AS u"
                    . " JOIN " . USERCREDTABLE . " AS uc ON u.id=uc.user_id" 
                    . " WHERE uc.username=:username";
            $stmt = $this->dbh->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Bfs\\Database\\Dao\\UserDao");
            $stmt->execute(array(':username' => $user->username));
            
            $userDao = $stmt->fetch();
            
            if (!($userDao instanceof UserDao)) {
                return $this->error(ErrorCodes::USER_GENERIC_ERROR
                        , "Failed to read user");
            }
            
            return $userDao;
        } catch (PDOException $ex) {
            $syslog = new Syslog();
            $syslog->write($ex);
            $syslog->shutdown();
            
            return $this->error(ErrorCodes::USER_GENERIC_ERROR
                    , "Failed to read user");
        }
    }
    
    public function userExists(UserDao $user) {
        try {
            $sql = "SELECT u.id FROM " . USERTABLE . " AS u"
                    . " JOIN " . USERCREDTABLE . " AS uc ON uc.user_id=u.id"
                    . " WHERE uc.username=:username";
            $stmt = $this->dbh->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute(array(':username' => $user->username));           
            
            $rowCount = count($stmt->fetchAll());
            $stmt->closeCursor();
            
            return $rowCount > 0;
        } catch (PDOException $ex) {
            $syslog = new Syslog();
            $syslog->write($ex);
            $syslog->shutdown();
            
            return $this->error(ErrorCodes::USER_GENERIC_ERROR
                    , "Failed to find user");
        }
    }
    
    private static function hasRequired(UserDao $user) {
        return (isset($user->first_name) 
                && isset($user->last_name)
                && isset($user->username)
                && isset($user->password)
                && isset($user->dob)
                && isset($user->email));
    }
    
    private function error($errorCode, $msg) {
        return array(
            'error' => true,
            'code'  => $errorCode,
            'msg'   => $msg
        );
    }
    
}
