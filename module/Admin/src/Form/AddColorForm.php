<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Application\Entity\Category;
use Application\Entity\ProductMaster;
use Zend\Validator\Regex;

/**
 * This form is used to collect post data.
 */
class AddColorForm extends Form
{
    private $size = [
        ProductMaster::S => 'S',
        ProductMaster::M => 'M',
        ProductMaster::L => 'L',
        ProductMaster::XL => 'XL',
        ProductMaster::XXL => 'XXL',
        ];

    private $color; 
    /**
     * Constructor.     
     */
    public function __construct($color)
    {
        // Define form name
        parent::__construct('add-color-form');
     
        // Set POST method for this form

        $this->setAttributes([
            'method'    => 'POST',
            'class'     => 'form-horizontal',
            'role'      => 'form',
            'name'      => 'AddColorForm',
            'id'        => 'add-color-form',
            'enctype'   => 'multipart/form-data'
        ]);
        $this->color = $color;
      
        $this->addElements();
        $this->addInputFilter();    
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() 
    {        
        // Add "Name" field
        $this->add([
            'name' => 'color',
            'type' => 'Select',
            'attributes' => [
                'class' => 'form-control width-custom',
                ],
            'options' => [
                'value_options' => $this->color,
                'label' => 'Color :',
                ],    
            ]);
                
        $this->add([
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'size',
            'options' => [
                'label' => 'Size : ',
                'value_options' => $this->size,
            ]
        ]);
        
        $this->add([
            'name' => 'image-details[]',
            'type' => 'File',
            'attributes' => [
                'class' => 'file form-control width-custom',
                'id' => 'image',
                ],
            'options' => [
                'label' => 'Picture',
                ],
            ]);
        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Create',
                'class' => 'btn btn-primary fileinput-upload',
                'id' => 'btn_submit_category',
            ],
        ]);
    }
    private function addInputFilter() 
    {
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
        
        $inputFilter->add([
            'name'     => 'size',
            'required' => true
        ]);
    }
}
