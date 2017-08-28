<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * This form is used to collect user's email, full name, password and status. The form
 * can work in two scenarios - 'create' and 'update'. In 'create' scenario, user
 * enters password, in 'update' scenario he/she doesn't enter password.
 */
class ShippingForm extends Form
{
    /**
     * Scenario ('create' or 'update').
     * @var string
     */
    private $scenario;

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Current user.
     * @var User\Entity\User
     */
    private $user = null;

    /**
     * Constructor.
     */
    public function __construct($scenario = 'create', $entityManager = null, $user = null)
    {
        // Define form name
        parent::__construct('shipping-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->user = $user;

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
        // Add "full_name" field
        $this->add([
            'type' => 'text',
            'name' => 'full_name',
            'options' => [
                'label' => 'Your name',
            ],
        ]);

        // Add "address" field
        $this->add([
            'type' => 'text',
            'name' => 'address',
            'options' => [
                'label' => 'Address',
            ],
        ]);

        // Add "phone-number" field
        $this->add([
            'type' => 'text',
            'name' => 'phone',
            'options' => [
                'label' => 'Phone',
            ],
        ]);

//        // Add "status" field
//        $this->add([
//            'type'  => 'select',
//            'name' => 'status',
//            'options' => [
//                'label' => 'Status',
//                'value_options' => [
//                    1 => 'Active',
//                    2 => 'Retired',
//                ]
//            ],
//        ]);

        // Add the Submit button
        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Place your order'
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


        // Add input for "address" field
        $inputFilter->add([
            'name' => 'address',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 512
                    ],
                ],
            ],
        ]);

        // Add input for "phone" field
        $inputFilter->add([
            'name' => 'phone',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 8,
                        'max' => 15
                    ],
                ],
            ],
        ]);

        // Add input for "full_name" field
        $inputFilter->add([
            'name' => 'full_name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 512
                    ],
                ],
            ],
        ]);
    }
}
