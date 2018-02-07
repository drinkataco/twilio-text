<?php
namespace App\Controller\Message;

use App\Service\MessageService;
use App\Entity\Message;

use Doctrine\ORM\EntityManagerInterface;

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
     * Entity manager for doctrine
     *
     * @var Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * Autowire services
     *
     * @param EntityManagerInterface $em Doctrine entity manager
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * View All Messages
     */
    public function viewAll(
        Request $request,
        MessageService $messageService
    ): Response {
        $reposity = $this->em->getRepository(Message::class);
        $messages = $reposity->getMessages();

        // Page Amount
        $totalPages = ceil($reposity->getTotalMessages() / $reposity->getPageSize());

        return $this->render(
            'message/view_messages.html.twig',
            array(
                'messages' => $messages,
                'page_number' => 1,
                'total_pages' => $totalPages
            )
        );
    }
}
