<?php
namespace Application\Form;

use Zend\InputFilter;
use Zend\Form\Form;

class UserLogin extends Form
{

    public function __construct()
    {
        parent::__construct(null, array());
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->addElements();
        $this->setInputFilter($this->createInputFilter());
    }

    public function addElements()
    {
        $this->add(
            array(
                'name' => 'mcsrf',
                'type' => 'Zend\Form\Element\Csrf',
                'options' => array(
                    'csrf_options' => array(
                        'timeout' => CSRF_TIMEOUT_SECONDS
                    )
                )
            ));
        
        $this->add(
            array(
                'name' => 'userPassword',
                'type' => 'Zend\Form\Element\Password',
                'attributes' => array(
                    'class' => 'form-control',
                    'id' => 'passwordI',
                    'placeholder' => 'Password',
                    'required' => 'required'
                ),
                'options' => array(
                    'label' => 'Password'
                )
            ));
        
        $this->add(
            array(
                'name' => 'userLoginId',
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                    'placeholder' => 'Login Id',
                    'class' => 'form-control',
                    'id' => 'loginidI',
                    'required' => 'required'
                ),
                'options' => array(
                    'label' => 'Login Id'
                )
            ));
    }

    public function createInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();
        
        $inputFilter->add(
            array(
                'name' => 'userLoginId',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 150
                        )
                    )
                ),
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                )
            ));
        $inputFilter->add(
            array(
                'name' => 'userPassword',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max' => 200
                        )
                    )
                )
            ));
        return $inputFilter;
    }
}