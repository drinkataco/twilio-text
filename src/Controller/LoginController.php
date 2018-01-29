<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller for Login / Logout
 */
class LoginController extends Controller
{
    /**
     * Handles Login
     *
     * @param Request $request The Request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        // $user = new User();
        // $form = $this->createForm(UserType::class, $user);
        // $form->handleRequest($request);

        // We want to render the form if GET, otherwise process is
        if ($request->getMethod() === Request::METHOD_GET) {
            return $this->render('user/login.html.twig');
        } else {
            return new Response('POST ey');
        }
    }

    public function logout(Request $request)
    {
    }
}
