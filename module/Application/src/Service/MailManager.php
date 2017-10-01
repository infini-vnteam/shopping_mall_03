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

    public function sendOrder($hash, $data) {
        
        $message = new Message();
        $message->addFrom('infinishop.vnteam@gmail.com', 'InfiniShop');
        $message->addTo($data['email'], $data['full_name']);
        $message->setSubject('Thank for buy');
        $message->setBody('Thankyou for buy my product! There is your order code: ' . $hash . '.If you want see your order, please tracking in my site ');
        $this->transport->send($message);
    } 
}
