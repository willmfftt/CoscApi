<?php
namespace Bfs\V1\Rest\Band;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Bfs\Database\Connection;
use Bfs\Database\Band;
use Bfs\Database\Dao\BandDao;
use Bfs\Login\Login;
use Bfs\Database\Dao\UserDao;
use Bfs\ErrorCodes;

class BandResource extends AbstractResourceListener
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
                'code'  => ErrorCodes::NOT_MODERATOR,
                'msg'   => "Only moderators can create bands"
            );
        }
        
        $bandDao = new BandDao();
        $bandDao->name = $data->name;
        $bandDao->date_start = $data->date_start;
        
        $conn = new Connection();
        $band = new Band($conn->getPdo());
        
        return $band->create($bandDao);
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
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
        return new ApiProblem(405, 'The GET method has not been defined for collections');
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
