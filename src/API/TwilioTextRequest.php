<?php
namespace App\API;

use App\Entity\Message;

use Symfony\Component\Dotenv\Dotenv;

use Twilio\Exceptions\RestException as TwilioRestException;
use Twilio\Rest\Client as TwilioClient;

/**
 * Twilio API controller class
 *
 * @author Josh Walwyn <me@joshwalwyn.com>
 */
class TwilioTextRequest
{
    /**
     * Twilio Client
     *
     * @var Twilio\Rest\Client
     */
    private $client;

    /**
     * From Phone Number
     *
     * @var string
     */
    private $fromNumber;

    /**
     * If an exception was thrown, this was it
     *
     * @var string
     */
    private $exception;

    /**
     * If known, the response status code
     *
     * @var int
     */
    private $statusCode;

    /**
     * Initialise Client
     */
    public function __construct()
    {
        $sid = getenv('TWILIO_SID');
        $token = getenv('TWILIO_TOKEN');
        $this->fromNumber = getenv('TWILIO_FROM_NUMBER');

        $this->client = new TwilioClient($sid, $token);
    }

    /**
     * Send a text message
     *
     * @param  Message $msg Message Entity
     *
     * @return bool success or failure
     */
    public function sendMessage(
        Message $msg
    ): bool {
        // Send Message
        try {
            $this->client->messages->create(
                $msg->getRecipient(),
                array(
                    'from' => $this->fromNumber,
                    'body' => $msg->getMessageBody()
                )
            );

            return true;
        } catch (TwilioRestException $restEx) {
            $this->statusCode = $restEx->getStatusCode();
            $this->exception = $restEx->getMessage();
        } catch (\Exception $ex) {
            $this->exception = $ex->getMessage();
        }
        return false;
    }

    /**
     * Request exception, if one was thrown
     *
     * @return string the exception
     */
    public function getException(): string
    {
        return $this->exception;
    }

    /**
     * Status code (if known)
     *
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
}
