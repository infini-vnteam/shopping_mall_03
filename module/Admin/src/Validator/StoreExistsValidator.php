<?php
namespace Admin\Validator;

use Zend\Validator\AbstractValidator;
use Application\Entity\Store;
/**
 * This validator class is designed for checking if there is an existing 
 * with such an store_name.
 */
class StoreExistsValidator extends AbstractValidator 
{
    /**
     * Available validator options.
     * @var array
     */
    protected $options = [
        'entityManager' => null,
        'store' => null
        ];
    
    // Validation failure message IDs.
    const NOT_SCALAR  = 'notScalar';
    const STORE_EXISTS = 'storeExists';
    
    /**
     * Validation failure messages.
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_SCALAR  => "The store must be a scalar value",
        self::STORE_EXISTS  => "Another store with such an name already exists"        
        ];
    
    /**
     * Constructor.     
     */
    public function __construct($options = null) 
    {
        // Set filter options (if provided).
        if(is_array($options)) {            
            if(isset($options['entityManager']))
                $this->options['entityManager'] = $options['entityManager'];
            if(isset($options['store']))
                $this->options['store'] = $options['store'];
        }
        
        // Call the parent class constructor
        parent::__construct($options);
    }
    
    /**
     * Check if store exists.
     */
    public function isValid($value) 
    {
        if(!is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false; 
        }
        
        // Get Doctrine entity manager.
        $entityManager = $this->options['entityManager'];
        
        $store = $entityManager->getRepository(Store::class)->findOneByName($value);   
        if($this->options['store']==null) {
            $isValid = ($store==null);
        } else {
            if($this->options['store']->getName()!=$value && $store!=null) 
                $isValid = false;
            else 
                $isValid = true;
        }
        
        // If there were an error, set error message.
        if(!$isValid) {            
            $this->error(self::STORE_EXISTS);            
        }
        
        // Return validation result.
        return $isValid;
    }
}

