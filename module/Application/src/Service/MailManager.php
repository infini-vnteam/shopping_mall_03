<?php
namespace Application\Service;

use Zend\Mail\Message;

class MailManager 
{
    private $transport;
    public function __construct($transport)
    {
        $this->transport = $transport;
    }

    public function send($message)
    {
        $this->transport->send($message);
        return 'ok';
    }

    public function sendOrder($order_id) {
        
        $message = new Message();
        $message->addFrom('infinishop.vnteam@gmail.com', 'InfiniShop');
        $message->addTo('infinishop.vnteam@gmail.com', 'Jane Doe');
        $message->setSubject('Thank for buy');
        $message->setBody('Thankyou for buy my product! There is my order code: ' . $order_id);
        $this->transport->send($message);
    } 
}