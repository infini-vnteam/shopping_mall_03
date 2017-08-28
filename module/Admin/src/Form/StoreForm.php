<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Application\Entity\Store;
use Zend\Validator\Regex;
use Admin\Validator\StoreExistsValidator;
/**
 * This form is used to collect 
 */
class StoreForm extends Form
{
   /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager = null; 
    /**
     * Current store.
     * @var Application\Entity\Store 
     */
    private $store = null;

    public function __construct($entityManager = null, $store = null){
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->store = $store;
        // FORM Attribute
        $this->setAttributes([
            'action'    => '#',
            'method'    => 'POST',
            'role'      => 'form',
            'name'      => 'StoreForm',
            'id'        => 'StoreForm',
            'enctype'   => 'multipart/form-data'
            ]);

        // ID
        $this->add([
            'name'          => 'id',
            'attributes'    => [
                'type'  => 'hidden',
            ],
            ]);
        $this->add([
            'name'          => 'name',
            'type'          => 'text',
            'attributes'    => [
                'class'         => 'form-control',
                'id'            => 'name',
                'placeholder'   => 'Enter store name : ',
                ],
            'options'       => [
                'label'             => 'Store name :',
                'label_attributes'  => [
                    'for'       => 'name',
                    'class'     => 'control-label ',
                    ]
                ],
            ]);
        $this->add([
            'name'          => 'address',
            'type'          => 'text',
            'attributes'    => [
                'class'         => 'form-control',
                'id'            => 'address',
                'placeholder'   => 'Enter store address : ',
                ],
            'options'       => [
                'label'             => 'Store address :',
                'label_attributes'  => [
                    'for'       => 'address',
                    'class'     => 'control-label ',
                    ]
                ],
            ]);
        $this->add([
            'name'          => 'phone',
            'type'          => 'text',
            'attributes'    => [
                'class'         => 'form-control',
                'id'            => 'phone',
                'placeholder'   => 'Enter phone of store  : ',
                ],
            'options'       => [
                'label'             => 'Store phone :',
                'label_attributes'  => [
                    'for'       => 'phone',
                    'class'     => 'control-label ',
                    ]
                ],
            ]);
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Create',
                'id' => 'submitbutton',
                'class' => 'btn btn-primary'
                ],
            ]);           
        $this->addInputFilter();

    }
    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter() 
    { 
       // Create main input filter
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
       // Name
        $inputFilter->add([
            'name'      => 'name',
            'required'  => true,
            'validators'    => 
            [
                [
                    'name'      => 'NotEmpty',
                ],       
                
                [
                    'name'      => 'StringLength',
                    'options'   => array('min' => 4, 'max' => 200),
                ],
                [
                'name' => StoreExistsValidator::class,
                'options' => [
                    'entityManager' => $this->entityManager,
                    'store' => $this->store
                    ],
                
                ],
            
            ],
        ]); 
        // Add input for "address" field
        $inputFilter->add([
            'name'     => 'address',
            'required' => true,
            'filters'  => [                    
                ['name' => 'StringTrim'],
            ],                
            'validators' => [
                [   
                'name'    => 'StringLength',
                'options' => [
                    'min' => 8,
                    'max' => 8096
                    ],
                ],
            ],
        ]);
        //phone
        $inputFilter->add([
            'name'     => 'phone',
            'required' => true,
            'validators'  => [                    
                ['name'     => 'Digits',],
            ],                   
        ]);        
    }       
}
