<?php

namespace Bfs\Database;

use PDO;
use PDOException;
use Bfs\Database\Dao\BandMemberRelDao;
use Zend\Log\Writer\Syslog;

/**
 * 
 *
 * @author William Moffitt
 */
class BandMemberRel {
    
    private $dbh;
    
    public function __construct(PDO $dbh) {
        $this->dbh = $dbh;
    }
    
    public function create(BandMemberRelDao $dao) {
        try {
            $sql = "INSERT INTO " . BANDMEMBERRELTABLE 
                    . "(band_id, band_member_id, date_start) VALUES (:band_id, :band_member_id, :date_start)";
            $stmt = $this->dbh->prepare($sql);
            $result = $stmt->execute(array(
                ':band_id'        => $dao->band_id,
                ':band_member_id' => $dao->band_member_id,
                ':date_start'     => $dao->date_start
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
    
    public function delete(BandMemberRelDao $dao) {
        try {
            $sql = "UPDATE " . BANDMEMBERRELTABLE 
                    . " SET date_thru=:date_thru WHERE band_id=:band_id AND band_member_id=:band_member_id";
            $stmt = $this->dbh->prepare($sql);
            $result = $stmt->execute(array(
                ':date_thru'      => $dao->date_thru,
                ':band_id'        => $dao->band_id,
                ':band_member_id' => $dao->band_member_id
            ));
            $stmt->closeCursor();
            
            if (!$result) {
                return array(
                    'error' => true
                );
            }
            
            return array(
                'error' => false
            );
        } catch (Exception $ex) {
            $syslog = new Syslog();
            $syslog->write($ex);
            $syslog->shutdown();
            
            return array(
                'error' => true
            );
        }
    }
    
}
