<?php
namespace Application\Form;

use Zend\InputFilter;
use Zend\Form\Form;

class AgentPayments extends Form
{

    protected $test;

    public function __construct($test)
    {
        parent::__construct($name = null, $options = array());
        $this->addElements();
        $this->setInputFilter($this->createInputFilter());
    }

    public function addElements()
    {
        $this->add(array(
            'name' => 'paymentAmount',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'id' => 'paymentAmountI',
                'placeholder' => 'Please enter the amount to pay'
            ),
            'options' => array(
                'label' => 'Please enter the amount to pay'
            )
        ));
        $this->add(array(
            'name' => 'verifyAction',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'id' => 'verifyActionI',
                'placeholder' => 'Enter the transaction password'
            ),
            'options' => array(
                'label' => 'Enter the transaction password'
            )
        ));
        $this->add(array(
            'name' => 'mcsrf',
            'type' => 'Zend\Form\Element\Csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => CSRF_TIMEOUT_SECONDS
                )
            )
        ));
    }

    public function createInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();
        
        $reqIps = array(
            'paymentAmount',
            'verifyAction'
        );
        
        foreach ($reqIps as $inputName) {
            $inputFilter->add(array(
                'name' => $inputName,
                'required' => true
            ));
        }
        
        return $inputFilter;
    }
}

