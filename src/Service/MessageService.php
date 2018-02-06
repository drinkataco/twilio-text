<?php
namespace App\Service;

use App\Entity\Message;
use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Controller used to manage the creation of messages
 *
 * @author Josh Walwyn <me@joshwalwyn.com>
 */
class MessageService
{
    /**
     * Key for user
     */
    const USER_KEY = 'user';

    /**
     * Rate limit time (s)
     */
    const RATE_LIMIT = 15;

    /**
     * Internal Cache
     *
     * @var Predis\Client
     */
    private $cache;

    /**
     * Rabbit MQ Producer client
     *
     * @var OldSound\RabbitMqBundle\RabbitMq\Producer
     */
    private $queue;

    /**
     * Entity manager for doctrine
     *
     * @var Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * Autowire services
     *
     * @param Container              $container Main container - to load rabbit mq service
     * @param AdapterInterface       $cache     Cache adapter - uses Redis
     * @param EntityManagerInterface $em        Doctrine entity manager
     */
    public function __construct(
        Container $container,
        AdapterInterface $cache,
        EntityManagerInterface $em
    ) {
        $this->cache = $cache;
        $this->queue = $container->get('old_sound_rabbit_mq.send_message_producer');
        $this->em    = $em;
    }

    /**
     * Add Message to database, and set default status
     *
     * @param Message $message Message Entity
     * @param User    $user    User Entity
     */
    public function addMessage(
        Message $message,
        User $user
    ): Message {
        $message->setStatus(Message::QUEUE_STATUS);
        $message->setUser($user);

        $this->em->persist($message);
        $this->em->flush();

        return $message;
    }

    /**
     * Identify whether use has hit the rate limit
     *
     * @param  string $userId current user Id
     *
     * @return bool true if rate limited, false if not
     */
    public function rateLimited(string $userId): bool
    {
        $lastSend = $this->cache->getItem(self::USER_KEY . ".$userId");
        $timeNow = time();

        if (is_null($lastSend) || ($timeNow - $lastSend->get()) > self::RATE_LIMIT) {
            $lastSend->set($timeNow);
            $this->cache->save($lastSend);

            return false; // is not rate limited
        }

        return true;
    }

    /**
     * Queue Up Message
     *
     * @param Message $message The Message to queue
     *
     * @return bool queued or not
     */
    public function queueMessage(Message $message): bool
    {
        $this->queue->publish(serialize($message));

        return true;
    }

    /**
     * Get Messages
     */
    public function getMessages()
    {
        $rep = $this->em->getRepository(Message::class);
        $e = $rep->findBy(array(), array('createdDate' => 'DESC'));

        return $e;
    }

    /**
     * Update message after sent
     */
    public function updateMessage(
        Message $message,
        bool $status
    ) {
        $sendStatus = ($status === true) ? Message::SENT_STATUS : Message::FAILURE_STATUS;

        $message->setSentDate(new \DateTime());
        $message->setStatus($sendStatus);

        $this->em->merge($message);
        $this->em->flush();
    }
}
