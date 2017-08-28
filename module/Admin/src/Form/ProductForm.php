<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Regex;
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
    private $stores;
    private $color = [
        '1' => 'White',
        '2' => 'Black',
        '3' => 'Blue',
        '4' => 'Yellow',
        '5' => 'Red',
        '6' => 'Green',
        '7' => 'Purple',
        '8' => 'Orange',
        '9' => 'Light blue',
        '10' => 'Sky blue',
        '11' => 'Gray',
        ];

    public function __construct(
                                $scenario,
                                $categories,
                                $stores, 
                                $entityManager,
                                $product = null
                                )
    {
        parent::__construct();

        $this->categories = $categories;
        $this->stores = $stores;
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

        // Store
        $this->add([
            'name' => 'store_id',
            'type' => 'Select',
            'options' => [
                'value_options' => $this->stores,
                'label' => 'Store',
                ],
            'attributes' => [
                'class' => 'form-control width-custom',
                ],
            ]);

        // Sale value
        $this->add([
            'name' => 'sale',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control width-custom',
                'id' => 'sale',
                'placeholder' => 'Enter sale off value:',
                ],
            'options' => [
                'label' => 'Sale off value:',
                ],
            ]);

        //Quantity
        $this->add([
            'name' => 'quantity',
            'type' => 'Text',
            'attributes' => [
                'class' => 'form-control width-custom',
                'id' => 'quantity',
                'placeholder' => 'Enter quantity :',
                ],
            'options' => [
                'label' => 'Quantity :',
                ],
            ]);

        //Color
        $this->add([
            'name' => 'color',
            'type' => 'Select',
            'attributes' => [
                'class' => 'form-control width-custom',
                'id' => 'color',
                ],
            'options' => [
                'value_options' => $this->color,
                'label' => 'Color :',
                ],    
            ]);

        //Size
        $this->add([
            'name' => 'size',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control width-custom',
                'id' => 'size',
                ],
            'options' => [
                'label' => 'Size :',
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

            $this->add([
                'name' => 'popular_level',
                'type' => 'Select',
                'attributes' => [
                    'class' => 'form-control width-custom',
                    'id' => 'popular_level',
                    ],
                'options' => [
                    'empty_option' => '-- Select type --',
                    'value_options' => [
                        '1' => 'Special',
                        '0' => 'Normal',
                        ],
                    'label' => 'Product type :',
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
        $inputFilter->add([
            'name'     => 'size',
            'required' => true,
            'validators'  => [                    
                ['name'     => 'Digits',],
                ],                   
            ]);

        //Quantity
        $inputFilter->add([
            'name'     => 'quantity',
            'required' => true,
            'validators'  => [                    
                ['name'     => 'Digits',],
                ],                   
            ]);    
        
        //if form is edit form
        if($this->scenario == 'edit'){
            //sale 
            $inputFilter->add(
                [
                'name'      => 'sale',
                'required'  => false,
                'validators'  => [                    
                    ['name'     => 'Digits',],
                    ], 
                ]
                );
            
            //status 
            $inputFilter->add(
                [
                'name'      => 'status',
                'required'  => true,
                ]
                );
            
            //popular_level 
            $inputFilter->add(
                [
                'name'      => 'popular_level',
                'required'  => true,
                ]
                );
        }
    }     
}
