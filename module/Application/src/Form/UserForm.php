<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Application\Validator\UserExistsValidator;
use Application\Entity\Province;

/**
 * This form is used to collect user's email, full name, password and status. The form
 * can work in two scenarios - 'create' and 'update'. In 'create' scenario, user
 * enters password, in 'update' scenario he/she doesn't enter password.
 */
class UserForm extends Form
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
        parent::__construct('user-form');

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
        //Get Pre-province and district to form
        $provinces = $this->entityManager->getRepository(Province::class)->findAll();   
        foreach ($provinces as $p) {
            $provinces_for_select[$p->getId()] = $p->getName(); 
        }

        $province = $this->entityManager->getRepository(Province::class)->find(1);
        $districts = $province->getDistricts();
        foreach ($districts as $d) {
            $districts_for_select[$d->getId()] = $d->getName();
        }

        // Add "full_name" field
        $this->add([
            'type' => 'text',
            'name' => 'full_name',
            'options' => [
                'label' => 'Your name',
            ],
        ]);

        if ($this->scenario == 'create') {
            // Add "email" field
            $this->add([
                'type' => 'text',
                'name' => 'email',
                'options' => [
                    'label' => 'Email',
                ],
            ]);

            // Add "password" field
            $this->add([
                'type' => 'password',
                'name' => 'password',
                'options' => [
                    'label' => 'Password',
                ],
            ]);

            // Add "confirm_password" field
            $this->add([
                'type' => 'password',
                'name' => 'confirm_password',
                'options' => [
                    'label' => 'Re-enter password',
                ],
            ]);
        } else {
            $this->add([
                'type'  => 'select',
                'name' => 'province',
                'attributes' => [
                    'class' => 'form-control',                
                    'id' => 'province',
                ],
                'options' => [
                    'label' => 'Province',
                    'value_options' => $provinces_for_select,
                ],
            ]);
            $this->add([
                'type'  => 'select',
                'name' => 'district',
                'attributes' => [
                    'class' => 'form-control',                
                    'id' => 'district',
                ],
                'options' => [
                    'disable_inarray_validator' => true,
                    'label' => 'District',
                    'value_options' => $districts_for_select,
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
        }
 

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
                'value' => 'Create your account'
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

        if ($this->scenario == 'create') {
            // Add input for "email" field
            $inputFilter->add([
                'name' => 'email',
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                            'useMxCheck' => false,
                        ],
                    ],
                    [
                        'name' => UserExistsValidator::class,
                        'options' => [
                            'entityManager' => $this->entityManager,
                            'user' => $this->user
                        ],
                    ],
                ],
            ]);
        } else {
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
        }
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

        if ($this->scenario == 'create') {

            // Add input for "password" field
            $inputFilter->add([
                'name' => 'password',
                'required' => true,
                'filters' => [
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ],
                    ],
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 512
                        ],
                    ],
                ],
            ]);

            // Add input for "confirm_password" field
            $inputFilter->add([
                'name' => 'confirm_password',
                'required' => true,
                'filters' => [
                ],
                'validators' => [
                    [
                        'name' => 'Identical',
                        'options' => [
                            'token' => 'password',
                        ],
                    ],
                ],
            ]);
        }
//
//        // Add input for "status" field
//        $inputFilter->add([
//                'name'     => 'status',
//                'required' => true,
//                'filters'  => [
//                    ['name' => 'ToInt'],
//                ],
//                'validators' => [
//                    ['name'=>'InArray', 'options'=>['haystack'=>[1, 2]]]
//                ],
//            ]);
    }
}
