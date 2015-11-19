<?php

namespace Bfs\Database;

use PDO;
use PDOException;
use Bfs\ErrorCodes;
use Bfs\Database\Dao\BandDao;
use Zend\Log\Writer\Syslog;

/**
 * 
 *
 * @author William Moffitt
 */
class Band {
    
    private $dbh;
    
    public function __construct(PDO $dbh) {
        $this->dbh = $dbh;
    }
    
    public function create(BandDao $bandDao) {
        if (!self::hasRequired($bandDao)) {
            return self::error(ErrorCodes::BAND_INCOMPLETE
                    , "Band has missing parameters");
        }
        
        // Make sure band does not exist already
        
        
        try {
            $sql = "INSERT INTO " . BANDTABLE . " (name, date_start, modify_date) VALUES (:name, :date_start, now())";
            $stmt = $this->dbh->prepare($sql);
            $result = $stmt->execute(array(
                ':name'       => $bandDao->name,
                ':date_start' => $bandDao->date_start
            ));
            $stmt->closeCursor();
            
            if (!$result) {
                return self::error(ErrorCodes::BAND_GENERIC_ERROR
                        , "Failed to create band");
            }
            
            return array(
                'error' => false,
                'id'    => $this->dbh->lastInsertId()
            );
            
        } catch (PDOException $ex) {
            $syslog = new Syslog();
            $syslog->write($ex);
            $syslog->shutdown();
            
            return self::error(ErrorCodes::BAND_GENERIC_ERROR
                    , "Failed to create band");
        }
    }
    
    public function readAll() {
        try {
            $sql = "SELECT * FROM " . BANDTABLE . " WHERE date_thru IS NULL ORDER BY name";
            $stmt = $this->dbh->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            $bands = $stmt->fetchAll();
            $stmt->closeCursor();
            
            if (!$bands) {
                return array(
                    'error' => true
                );
            }

            return array(
                'error' => false,
                'bands' => $bands
            );
        } catch (PDOException $ex) {
            $syslog = new Syslog();
            $syslog->write($ex);
            $syslog->shutdown();
            
            return array(
                'error' => true
            );
        }
    }
    
    private static function hasRequired(BandDao $bandDao) {
        return (!(is_null($bandDao->name) || is_null($bandDao->date_start)));
    }
    
    private static function error($errorCode, $msg) {
        return array(
            'error' => true,
            'code'  => $errorCode,
            'msg'   => $msg
        );
    }
    
}
