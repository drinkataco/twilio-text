<?php
namespace App\Controller\User;

use App\Entity\User;
use App\Form\UserType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Controller used to manage user registration
 *
 * @author Josh Walwyn <me@joshwalwyn.com>
 */
class RegistrationController extends Controller
{
    /**
     * Handles Registration
     */
    public function registerAction(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {
        // Redirect user if already logged in
        if (!is_null($this->getUser())) {
            return $this->redirectToRoute('homepage');
        }

        // Build the User form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // Handle the form on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // Save the User to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // Redirect to main route page
            return $this->redirectToRoute('text');
        }


        // Otherwise just render page on GET
        return $this->render('user/register.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
