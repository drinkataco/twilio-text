<?php
namespace App\Controller\Message;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\Message;
use App\Form\MessageType;

use App\Service\MessageService as MessageService;

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
    public function message(
        Request $request,
        MessageService $messageService
    ): Response {
        // Build the Message form
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        // Handle to form on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();

            // If rate limted, don't send or save message
            if ($messageService->rateLimited($this->getUser()->getId())) {
                // Rate limited, don't send message
                $this->addFlash('danger', sprintf('Please wait at least %s seconds before sending another message', MessageService::RATE_LIMIT));

                // Retain form data
                $this->addFlash('form.recepient',   $message->getRecipient());
                $this->addFlash('form.messageBody', $message->getMessageBody());
            } else {
                $message = $messageService->addMessage($message, $this->getUser());

                $messageService->queueMessage($message);
                $this->addFlash('success', 'Text Message Queued!');
            }

            // Render page with queued message
            return $this->redirectToRoute('message');
        }

        // Otherwise just render page on GET
        return $this->render(
            'message/send_message.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * View All Messages
     */
    public function messages(
        Request $request,
        MessageService $messageService
    ): Response {
        return $this->render(
            'message/view_messages.html.twig',
            array('messages' => $messageService->getMessages())
        );
    }
}
