<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class SearchForm extends Form
{
    public function __construct()
    {
        parent::__construct('search-form');

        $this->setAttribute('method', 'get');

        $this->add([
            'type' => Element\Search::class,
            'name' => 'query',
            'attributes' => [
                'class' => 'form-control input-search',
                'id' => 'search-box',
                'placeholder' => 'Search for everything...',
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'class' => 'btn btn-search',
            ],
        ]);
	}
}
