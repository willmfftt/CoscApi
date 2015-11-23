<?php

namespace Bfs\Database;

use PDO;
use PDOException;
use Zend\Log\Writer\Syslog;
use Bfs\Database\Dao\BandMemberDao;
use Bfs\Database\Dao\BandDao;
use Bfs\Database\BandMemberRel;
use Bfs\Database\Dao\BandMemberRelDao;

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
    
    public function create(BandMemberDao $bandMemberDao, BandMemberRelDao $bandMemberRelDao) {
        try {
            $sql = "INSERT INTO " . BANDMEMBERTABLE . "(first_name, last_name) VALUES (:first_name, :last_name)";
            $stmt = $this->dbh->prepare($sql);
            $result = $stmt->execute(array(
                ':first_name' => $bandMemberDao->first_name,
                ':last_name'  => $bandMemberDao->last_name
            ));
            $stmt->closeCursor();
            
            if (!$result) {
                return array(
                    'error' => true
                );
            }
            
            $bandMemberRelDao->band_member_id = $this->dbh->lastInsertId();
            $bandMemberRel = new BandMemberRel($this->dbh);
            $result = $bandMemberRel->create($bandMemberRelDao);
            
            if ($result['error']) {
                return $result;
            }
            
            return array(
                'error' => false,
                'id'    => $bandMemberRelDao->band_member_id
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
    
    public function readMembersForBand(BandDao $dao) {
        try {
            $sql = "SELECT bm.first_name, bm.last_name, bmr.date_start FROM " . BANDMEMBERRELTABLE . " AS bmr "
                    . "JOIN " . BANDTABLE . " AS b "
                    . "ON bmr.band_id=b.id "
                    . "JOIN " . BANDMEMBERTABLE . " AS bm "
                    . "ON bmr.band_member_id=bm.id "
                    . "WHERE b.id=:band_id AND bmr.date_thru IS NULL";
            $stmt = $this->dbh->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->execute(array(':band_id'=>$dao->id));
            
            if (!$result) {
                return array(
                    'error' => true
                );
            }
            
            $data = $stmt->fetchAll();
            $stmt->closeCursor();
            
            return array(
                'error'        => false,
                'band_members' => $data
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
