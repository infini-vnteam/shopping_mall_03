<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\MailManager;
use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

/**
 * This is the factory class for CategoryManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class MailManagerFactory
{
    /**
     * This method creates the CategoryManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        
        $transport = new SmtpTransport();
		$options   = new SmtpOptions([
		    'name' => 'smtp.gmail.com',
		    'host' => 'smtp.gmail.com',
		    

		    'connection_class'  => 'login',
		    'connection_config' => [
		        'username' => 'infinishop.vnteam@gmail.com',
		        'ssl' => 'TLS',
		        'port' => '465',
		        'password' => 'infinishop123456',
		    ],
		]);
		
		$transport->setOptions($options);
                        
        return new MailManager($transport);
    }
}
