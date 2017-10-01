<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Application\Entity\SaleProgram;
use Zend\Validator\Regex;

/**
 * This form is used to collect post data.
 */
class SaleProgramForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('saleprogram-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();    
    }

    private function addElements()
    {
        $this->add([
            'name' => 'name',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control',
                'id' => 'name',
                'placeholder' => 'Sale program name : ',
                ],
            'options' => [
                'label' => 'Name :',
                'label_attributes' => [
                    'for' => 'name',
                    'class' => 'control-label ',
                    ]
                ],
            ]);
        $this->add([
            'name' => 'date_start',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control',
                'id' => 'date-start',
                'placeholder' => 'Enter sale program starting date: ',
                ],
            'options' => [
                'label' => 'Date Start :',
                'label_attributes' => [
                    'class' => 'control-label ',
                    ]
                ],
            ]);
        $this->add([
            'name' => 'date_end',
            'type' => 'text',
            'attributes' => [
                'disabled' => 'disabled',
                'class' => 'form-control',
                'id' => 'date-end',
                'placeholder' => 'Enter sale program ending date: ',
                ],
            'options' => [
                'label' => 'Date End :',
                'label_attributes' => [
                    'class' => 'control-label ',
                    ]
                ],
            ]);
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
            'name'     => 'date_start',
            'required' => true,
        ]);

        $inputFilter->add([
            'name'     => 'date_end',
            'required' => false,
        ]);

    }
}
