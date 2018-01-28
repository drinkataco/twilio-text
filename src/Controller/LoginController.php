<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoginController extends Controller
{
    public function login()
    {
        $number = mt_rand(0, 100);

        return $this->render('user/login.html.twig');
    }
}