<?php
namespace App\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controller for Login / Logout
 *
 * @author Josh Walwyn <me@joshwalwyn.com>
 */
class SecurityController extends Controller
{
    /**
     * Handles User Login
     */
    public function login(Request $request, AuthenticationUtils $authUtils): Response
    {
        // Redirect user if already logged in
        if (!is_null($this->getUser())) {
            return $this->redirectToRoute('homepage');
        }

        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('user/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
}
