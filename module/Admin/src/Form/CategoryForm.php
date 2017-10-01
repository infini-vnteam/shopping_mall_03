<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Application\Entity\Category;
use Zend\Validator\Regex;

/**
 * This form is used to collect post data.
 */
class CategoryForm extends Form
{
    private $categories;
    /**
     * Constructor.     
     */
    public function __construct($categories)
    {
        // Define form name
        parent::__construct('category-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        $this->categories = $categories;         
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
            'type'  => 'text',
            'name' => 'name',
            'attributes' => [
                'class' => 'form-control',
                'id' => 'name',
                'placeholder'   => 'Enter Category name : '
            ],
            'options' => [
                'label' => 'Category Name',
            ],
        ]);
                
        // Add "Description" field
        $this->add([
            'type'  => 'textarea',
            'name' => 'description',
            'attributes' => [
                'class' => 'form-control',                
                'id' => 'description',
            ],
            'options' => [
                'label' => 'Description',
            ],
        ]);

        $this->add([
            'type'  => 'select',
            'name' => 'parent_id',
            'attributes' => [
                'class' => 'form-control',                
                'id' => 'parent_id',
            ],
            'options' => [
                'label' => 'Parent Category',
                'value_options' => $this->categories,
            ],
        ]);
        
        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Create',
                'class' => 'btn btn-primary',
                'id' => 'btn_submit_category',
            ],
        ]);
    }
    
    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter() 
    {
        
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
        
        $inputFilter->add([
            'name'     => 'name',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],                
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 32
                    ],
                ],
            ],
        ]);
        
        $inputFilter->add([
            'name'     => 'description',
            'required' => false,
            'filters'  => [
                ['name' => 'StringTrim'],                    
                ['name' => 'StripTags'],
            ],                
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 4096
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'parent_id',
            'required' => false,
        ]);
    }
}