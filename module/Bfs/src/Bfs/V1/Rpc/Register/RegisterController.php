<?php
namespace Bfs\V1\Rpc\Register;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use Bfs\Login\Register;
use Bfs\Database\Dao\UserDao;

class RegisterController extends AbstractActionController
{
    public function registerAction()
    {
        $data = json_decode($this->getRequest()->getContent());
        
        $userDao = new UserDao();
        $userDao->first_name = $data->first_name;
        $userDao->last_name = $data->last_name;
        $userDao->username = $data->username;
        $userDao->password = $data->password;
        $userDao->dob = $data->dob;
        $userDao->email = $data->email;
        
        $register = new Register();
        
        return new ViewModel($register->register($userDao));
    }
}
