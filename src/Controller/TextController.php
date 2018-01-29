<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TextController extends Controller
{
    public function text()
    {
        return $this->render('text/send_text.html.twig');
    }
}