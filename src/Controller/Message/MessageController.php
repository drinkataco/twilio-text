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

        // $redis = new PredisClient();


        // create a new item by trying to get it from the cache
        $cache =  $this->get('cache.app');
        $numProducts = $cache->getItem('stuff');

        dump($numProducts);

        die();

        // assign a value to the item and save it
        $numProducts->set(38473);
        $cache->save($numProducts);

        // retrieve the cache item
        // $numProducts = $cache->getItem('stats.num_products');
        // if (!$numProducts->isHit()) {
        //     // ... item does not exists in the cache
        // }
        // retrieve the value stored by the item
        $total = $numProducts->get();

        // remove the cache item
        // $cache->deleteItem('stats.num_products');
        dump($cache->getItems('*'));
        die();





        // Build the Message form
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        // Handle to form on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Send to cache service
            $cache =  $this->get('cache.app');

            $textsToSend = $cache->getItem('texts_to_send');


            // Send to Rabbit MQ



            return new Response('this has been sent');
        }

        // Otherwise just render page on GET
        return $this->render(
            'message/send_message.html.twig',
            array('form' => $form->createView())
        );
    }
}
