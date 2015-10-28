<?php
namespace Bfs\V1\Rpc\Login;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ContentNegotiation\ViewModel;
use Bfs\Login\Login;
use Bfs\Database\Dao\UserDao;

class LoginController extends AbstractActionController
{
    public function loginAction()
    {
       $data = json_decode($this->getRequest()->getContent());
       
       $userDao = new UserDao();
       $userDao->username = $data->username;
       $userDao->password = $data->password;
       
       $login = new Login();
       
       return new ViewModel($login->login($userDao));
    }
}
