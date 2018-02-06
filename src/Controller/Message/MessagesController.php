<?php
namespace App\Controller\Message;

use App\Service\MessageService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller used to manage the creation of messages
 *
 * @author Josh Walwyn <me@joshwalwyn.com>
 */
class MessagesController extends Controller
{
    /**
     * View All Messages
     */
    public function viewAll(
        Request $request,
        MessageService $messageService
    ): Response {
        $messages = $messageService->getMessages();

        return $this->render(
            'message/view_messages.html.twig',
            array('messages' => $messages)
        );
    }
}
