<?php
namespace App\Service;

use App\API\TwilioTextRequest;
use App\Entity\Message;
use App\Service\MessageService;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * Main Message Queue Consumer
 *
 * @author Josh Walwyn <me@joshwalwyn.com>
 */
class SendMessageProcess implements ConsumerInterface
{
    /**
     * Message Service Object
     *
     * @var App\Service\MessageService
     */
    private $messageService;

    /**
     * Autowire Message Service
     *
     * @param MessageService $messageService
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Consumer execute - send to twilio, and update message object
     *
     * @param  AMQPMessage $msg the message
     */
    public function execute(AMQPMessage $msg)
    {
        // Deserialise in to App\Entity\Message
        $message = unserialize($msg->body);

        // Send to Twilio
        $service = new TwilioTextRequest();
        $status = $service->sendMessage($message);

        // If service encounterred a server error, we know that
        // it should probably be requeued
        if (!$status &&
            !is_null($service->getStatusCode()) &&
            $service->getStatusCode() === 500
        ) {
            return false; // Will be retried
        }

        // Update new status
        $this->messageService->updateMessage($message, $status);

        return true;
    }
}
