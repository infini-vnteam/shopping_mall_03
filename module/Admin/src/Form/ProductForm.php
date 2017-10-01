<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Application\Entity\ProductMaster;
use Zend\Validator\Regex;
use Zend\Form\Element;
use Admin\Validator\ProductExistsValidator;
use Zend\InputFilter\InputFilterProviderInterface;

class ProductForm extends Form {

    private $scenario;
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
   
    /**
     * Current user.
     * @var Application\Entity\Product 
     */
    private $product;

    private $categories;

    public function __construct(
                                $scenario,
                                $categories,
                                $entityManager,
                                $product = null
                                )
    {
        parent::__construct();

        $this->categories = $categories;
       // $this->stores = $stores;
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->product = $product;

        $this->setAttributes([
            'method'    => 'POST',
            'class'     => 'form-horizontal',
            'role'      => 'form',
            'name'      => 'ProductForm',
            'id'        => 'ProductForm',
            'enctype'   => 'multipart/form-data'
            ]);

        $this->addElements();
        $this->addInputFilter();

    }

    protected function addElements()
    {
        // Name
        $this->add([
            'name' => 'name',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control width-custom',
                'id' => 'name',
                'placeholder' => 'Enter product name : ',
                ],
            'options' => [
                'label' => 'Product name :',
                ],
            ]);

        // Description
        $this->add([
            'name' => 'description',
            'type' => 'Textarea',
            'attributes'=> [
                'class' => 'form-control width-custom',
                'id' => 'description',
                ],
            'options' => [
                'label' => 'Description :',
                ],
            ]);

        // Price
        $this->add([
            'name' => 'price',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control width-custom',
                'id' => 'price',
                'placeholder' => 'Enter Price',
                ],
            'options' => [
                'label' => 'Price :',
                ],
            ]);

        // Intro
        $this->add([
            'name' => 'intro',
            'type' => 'Textarea',
            'attributes' => [
                'class' => 'form-control width-custom',
                'id' => 'intro',
                'placeholder' => 'Enter Intro :',
                ],
            'options' => [
                'label' => 'Intro :',
                ],
            ]);
        
        //Category
        $this->add([
            'name' => 'category_id',
            'type' => 'Select',
            'options' => [
                'value_options' => $this->categories,
                'label' => 'Category :',
                ],
            'attributes' => [
                'class' => 'form-control width-custom',
                ],
            ]);

        // File picture
        $this->add([
            'name' => 'image',
            'type' => 'File',
            'attributes' => [
                'class' => 'file form-control width-custom',
                'id' => 'image',
                ],
            'options' => [
                'label' => 'Picture',
                ],
            ]);

        //Keyword
        $this->add([
            'type'  => 'text',
            'name' => 'keywords',
            'attributes' => [      
                'class' => 'form-control width-custom',          
                'id' => 'keywords'
            ],
            'options' => [
                'label' => 'Keywords',
            ],
        ]);

        // If form is edit form
        if ($this->scenario == 'edit') {
            $this->add([
                'name' => 'status',
                'type' => 'Select',
                'options' => [
                    'empty_option' => '-- Select status --',
                    'value_options' => [
                        '1' => 'Active',
                        '0' => 'InActive',
                        ],
                    'label' => 'Status :',
                    ],
                'attributes' => [
                    'class' => 'form-control width-custom',
                    ],
                ]);   
        }

        //Submit button
        $this->add([
            'type' => 'submit',
            'name' => 'submit',

            'attributes' => [
                'class' => 'btn btn-primary',                
                'value' => 'Create',
                'id' => 'submitbutton',
                ],
            ]);
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
        if ($this->scenario == 'create') {
        $inputFilter->add([
            'name'      => 'name',
            'required'  => true,
            'validators'    => [
                [
                'name'      => 'NotEmpty',
                ],         
                [
                'name'      => 'StringLength',
                'options'   => array('min' => 4, 'max' => 200),       
                ],
                [
                'name' => ProductExistsValidator::class,
                'options' => [
                    'entityManager' => $this->entityManager,
                    'product' => $this->product
                    ],
                ],
                ],
            ]); 
        }
        // Add input for "description" field
        $inputFilter->add([
            'name'     => 'description',
            'required' => true,
            'filters'  => [                    
                ['name' => 'StringTrim'],
                ],                
            'validators' => [
                [
                'name'    => 'StringLength',
                'options' => [
                    'min' => 10,
                    'max' => 8096
                    ],
                ],
                ],
            ]);

        // Add input for "intro" field
        $inputFilter->add([
            'name'     => 'intro',
            'required' => true,
            'filters'  => [                    
                ['name' => 'StringTrim'],
            ],                
            'validators' => [
            [
                'name'    => 'StringLength',
                'options' => [
                    'min' => 1,
                    'max' => 8096
                    ],
            ],
            ],
            ]);
        
        //price
        $inputFilter->add([
            'name'     => 'price',
            'required' => true,
            'validators'  => [                    
                ['name'     => 'Digits',],
                ],                   
            ]);

        //Size
        // $inputFilter->add([
        //     'name'     => 'size',
        //     'required' => true,
        //     'validators'  => [                    
        //         ['name'     => 'Digits',],
        //         ],                   
        //     ]);  
        
        //if form is edit form
        if($this->scenario == 'edit'){
            
            //status 
            $inputFilter->add(
                [
                'name'      => 'status',
                'required'  => true,
                ]
                );
            
            
        }
    }     
}
