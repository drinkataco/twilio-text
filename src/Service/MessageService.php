<?php
namespace App\Service;

use App\Entity\Message;

use Predis\Autoloader;
use Predis\Client as Predis;

/**
 * Controller used to manage the creation of messages
 *
 * @author Josh Walwyn <me@joshwalwyn.com>
 */
class MessageService
{
    /**
     * Key for Text Message
     */
    const TEXT_KEY = 'text_message';

    /**
     * Key for user
     */
    const USER_KEY = 'user';

    /**
     * Rate limit time (s)
     */
    const RATE_LIMIT = 15;

    /**
     * Constant for pending/queued status
     */
    const QUEUE_STATUS = 'pending';

    /**
     * Constant for sent status
     */
    const SENT_STATUS = 'sent';

    /**
     * Constant for failed status
     */
    const FAILURE_STATUS = 'failed';

    /**
     * Redis Service Client
     *
     * @var Predis\Client
     */
    private $redis;

    /**
     * Load Redis Service and Client
     */
    public function __construct()
    {
        \Predis\Autoloader::register();
        $this->redis = new Predis();
    }

    /**
     * Add Message to Redis, and queue for sending
     *
     * @param String $recipient The Recipient (mobile number)
     * @param String $message   The Message Body
     * @param String $username  Username of sendee
     * @param Boolean $send     Whether to send (via queue)
     *
     * @return New Message Key
     */
    public function addMessage(
        string $recipient,
        string $message,
        string $username,
        bool $send = true
    ): string {
        // Make Key for text message
        $messageKey = self::TEXT_KEY . ":{$this->getMessageId()}";

        // Ensure queue time is within rate limit
        $queueTime = $this->calculateQueueTime($username);

        $this->redis->hmset(
            $messageKey,
            'recipient',
            $recipient,
            'message',
            $message,
            'user',
            $username,
            'status',
            self::QUEUE_STATUS,
            'queueDate',
            $queueTime,
            'createdDate',
            time()
        );

        return $messageKey;
    }

    /**
     * Get Message Identified by Key as entity
     *
     * @param  string $key Message Key
     *
     * @return App\Entity\Message message entity
     */
    public function getMessage(string $key): Message
    {
        $result = $this->redis->hgetall($key);

        $message = new Message();
        // $message.setCreatedDate();
        // $message.setRecipient();
        // $message.setMessageBody();

        // $message.setSentDate();
        // $message.setStatus();

        return $message;
    }

    public function getMessages()
    {
    }

    /**
     * Add
     * @param  string $messageId [description]
     * @return [type]            [description]
     */
    public function queueMessage(string $messageId)
    {
    }

    /**
     * Get Message ID value
     *
     * @return int New ID Value
     */
    private function getMessageId(): int
    {
        $key = self::TEXT_KEY . ':id';

        $this->redis->incr($key);

        return intval($this->redis->get($key));
    }

    /**
     * Calculate queue time to rate limit requests
     *
     * @param  String $username Current users username
     *
     * @return Integer timestamp to queue
     */
    private function calculateQueueTime(string $username): int
    {
        $queueTime = time();
        $userKey = self::USER_KEY . ":$username";

        // If rate limit is not met, just adjust to last que time + ratelimit
        $lastQueueTime = $this->redis->get($userKey);

        if (!is_null($lastQueueTime)) {
            $lastQueueTime = intval($lastQueueTime);

            if (($queueTime - $lastQueueTime) > self::RATE_LIMIT) {
                $queueTime = $lastQueueTime + self::RATE_LIMIT;
            }
        }

        // Commit to redis
        $this->redis->set($userKey, $queueTime);

        return $queueTime;
    }
}
