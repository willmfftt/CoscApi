<?php
namespace Bfs\V1\Rest\BandMember;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Bfs\Database\Dao\BandMemberDao;
use Bfs\Database\Connection;
use Bfs\Database\BandMember;
use Bfs\Database\BandMemberRel;
use Bfs\Database\Dao\BandMemberRelDao;
use Bfs\Database\Dao\UserDao;
use Bfs\Database\Dao\BandDao;
use Bfs\Login\Login;

class BandMemberResource extends AbstractResourceListener
{
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $userDao = new UserDao();
        $userDao->username = $data->username;
        $userDao->password = $data->password;
        
        $login = new Login();
        $result = $login->login($userDao);
        
        if ($result['error']) {
            return $result;
        }
        
        if ($result['is_moderator'] != 1) {
            return array(
                'error' => true,
                'msg'   => 'User must be moderator'
            );
        }
        
        $bandMemberDao = new BandMemberDao();
        $bandMemberDao->first_name = $data->first_name;
        $bandMemberDao->last_name = $data->last_name;
        
        $bandMemberRelDao = new BandMemberRelDao();
        $bandMemberRelDao->band_id = $data->band_id;
        $bandMemberRelDao->date_start = $data->date_start;
        
        $conn = new Connection();
        $bandMember = new BandMember($conn->getPdo());
        
        return $bandMember->create($bandMemberDao, $bandMemberRelDao);
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        $params = json_decode($this->getEvent()->getRequest()->getContent());
        
        if (!array_key_exists('date_thru', $params) 
                || !array_key_exists('band_id', $params)) {
            return false;
        }        
        $date_thru = $params->date_thru;
        $band_id = $params->band_id;
        
        $bandMemberRelDao = new BandMemberRelDao();
        $bandMemberRelDao->band_member_id = $id;
        $bandMemberRelDao->band_id = $band_id;
        $bandMemberRelDao->date_thru = $date_thru;
        
        $conn = new Connection();
        $bandMemberRel = new BandMemberRel($conn->getPdo());
        $result = $bandMemberRel->delete($bandMemberRelDao);
        
        if ($result['error']) {
            return false;
        } else {
            return true;
        }                
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        $userDao = new UserDao();
        $userDao->username = filter_input(INPUT_GET, 'username');
        $userDao->password = filter_input(INPUT_GET, 'password');
        
        $login = new Login();
        $result = $login->login($userDao);
        
        if ($result['error']) {
            return $result;
        }
        
        $bandDao = new BandDao();
        $bandDao->id = filter_input(INPUT_GET, 'band_id');
        
        $conn = new Connection();
        $bandMember = new BandMember($conn->getPdo());
        
        return $bandMember->readMembersForBand($bandDao);
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
