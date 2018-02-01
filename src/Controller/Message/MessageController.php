<?php
namespace App\Controller\Message;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\Message;
use App\Form\MessageType;

/**
 * Controller used to manage the creation of messages
 *
 * @author Josh Walwyn <me@joshwalwyn.com>
 */
class MessageController extends Controller
{
    /**
     * Handles Messaging
     */
    public function message(Request $request): Response
    {
        // Build the Message form
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        // Handle to form on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Send to REDIS Service

            return new Response('this has been sent');
        }

        // Otherwise just render page on GET
        return $this->render(
            'message/send_message.html.twig',
            array('form' => $form->createView())
        );
    }
}
