<?php

namespace Bfs\Database;

use PDO;
use PDOException;
use Zend\Log\Writer\Syslog;
use Bfs\Database\Dao\BandMemberDao;
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
    
}
