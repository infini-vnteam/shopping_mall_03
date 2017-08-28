<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to collect comment data.
 */
class CommentForm extends Form
{
    // Constructor.     
    public function __construct()
    {
        // Define form name
        parent::__construct('comment-form');
         
        // Set POST method for this form
        $this->setAttribute('method', 'post');
                    
        $this->addElements();
        $this->addInputFilter();         
    }
    
  // This method adds elements to form (input fields and submit button).
    protected function addElements() 
    {     
        // Add "comment" field
        $this->add([            
               'type'  => 'textarea',
                'name' => 'comment',
                'attributes' => [
                    'class' => 'form-control',
                    'id' => 'comment',
                    'required' => true,
                ],
                'options' => [
                    'label' => 'Comment',
                ],
            ]);
                    
        // Add the submit button
        $this->add([
                'type'  => 'submit',
                'name' => 'submit',
                'attributes' => [ 
                    'class' => 'btn btn-primary',               
                    'value' => 'Save',
                    'id' => 'submitbutton',
                    'style' => 'width: 100%',
                ],
            ]);
    }
    
  // This method creates input filter (used for form filtering/validation).
    private function addInputFilter() 
    {
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
                    
        $inputFilter->add([
                    'name'     => 'comment',
                    'required' => true,
                    'filters'  => [                    
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
    }
}
