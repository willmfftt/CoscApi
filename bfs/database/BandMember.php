<?php

namespace Bfs\Database;

use PDO;
use PDOException;
use Zend\Log\Writer\Syslog;
use Bfs\Database\Dao\BandMemberDao;

/**
 * 
 *
 * @author William Moffitt
 */
class BandMember {
    
    private $dbh;
    
    public function __construct(PDO $dbh) {
        $this->dbh = $dbh;
    }
    
    public function create(BandMemberDao $dao) {
        try {
            $sql = "INSERT INTO " . BANDMEMBERTABLE . "(first_name, last_name) VALUES (:first_name, :last_name)";
            $stmt = $this->dbh->prepare($sql);
            $result = $stmt->execute(array(
                ':first_name' => $dao->first_name,
                ':last_name'  => $dao->last_name
            ));
            $stmt->closeCursor();
            
            if (!$result) {
                return array(
                    'error' => true
                );
            }
            
            return array(
                'error' => false,
                'id'    => $this->dbh->lastInsertId()
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
    
}
