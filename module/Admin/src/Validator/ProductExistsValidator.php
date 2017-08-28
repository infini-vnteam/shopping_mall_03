<?php
namespace Admin\Validator;

use Zend\Validator\AbstractValidator;
use Application\Entity\Product;
/**
 * This validator class is designed for checking if there is an existing 
 * with such an product_name.
 */
class ProductExistsValidator extends AbstractValidator 
{
    /**
     * Available validator options.
     * @var array
     */
    protected $options = array(
        'entityManager' => null,
        'product' => null
        );
    
    // Validation failure message IDs.
    const NOT_SCALAR  = 'notScalar';
    const PRODUCT_EXISTS = 'productExists';
    
    /**
     * Validation failure messages.
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_SCALAR  => "The product must be a scalar value",
        self::PRODUCT_EXISTS  => "Another product with such an name already exists"        
        );
    
    /**
     * Constructor.     
     */
    public function __construct($options = null) 
    {
        // Set filter options (if provided).
        if(is_array($options)) {            
            if(isset($options['entityManager']))
                $this->options['entityManager'] = $options['entityManager'];
            if(isset($options['product']))
                $this->options['product'] = $options['product'];
        }
        
        // Call the parent class constructor
        parent::__construct($options);
    }
    
    /**
     * Check if product exists.
     */
    public function isValid($value) 
    {
        if(!is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false; 
        }
        
        // Get Doctrine entity manager.
        $entityManager = $this->options['entityManager'];
        
        $product = $entityManager->getRepository(Product::class)
        ->findOneByName($value);
        
        if($this->options['product']==null) {
            $isValid = ($product==null);
        } else {
            if($this->options['product']->getName()!=$value && $product!=null) 
                $isValid = false;
            else 
                $isValid = true;
        }
        
        // If there were an error, set error message.
        if(!$isValid) {            
            $this->error(self::PRODUCT_EXISTS);            
        }
        
        // Return validation result.
        return $isValid;
    }
}

