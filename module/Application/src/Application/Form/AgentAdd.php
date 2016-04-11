<?php
namespace Application\Form;

use Zend\InputFilter;
use Zend\Form\Form;

class AgentAdd extends Form
{

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->setInputFilter($this->createInputFilter());
    }

    public function addElements()
    {
        $this->add(
            array(
                'name' => 'name',
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                    'required' => 'required',
                    'placeholder' => 'Name',
                    'class' => 'form-control',
                    'id' => 'fullNameI'
                ),
                'options' => array(
                    'label' => 'Name'
                )
            ));
        $this->add(
            array(
                'name' => 'emailId',
                'type' => 'Zend\Form\Element\Email',
                'attributes' => array(
                    'required' => 'required',
                    'placeholder' => 'Email-Id',
                    'class' => 'form-control',
                    'id' => 'emailIdI'
                ),
                'options' => array(
                    'label' => 'Agent\'s Email-Id'
                )
            ));
        $this->add(
            array(
                'name' => 'mobile',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control',
                    'id' => 'mobileI',
                    'placeholder' => 'Mobile'
                ),
                'options' => array(
                    'label' => ''
                )
            ));
        $this->add(
            array(
                'name' => 'commissionPercentage',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control',
                    'id' => 'commissionI',
                    'placeholder' => 'Commission % (0-100)'
                ),
                'options' => array(
                    'label' => 'Commission % (0-100)'
                )
            ));
        $this->add(
            array(
                'name' => 'address',
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                    'required' => 'required',
                    'placeholder' => 'Agent\'s Address',
                    'class' => 'form-control',
                    'id' => 'emailIdI'
                ),
                'options' => array(
                    'label' => 'Agent\'s Address'
                )
            ));
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'enabled',
                'options' => array(
                    'empty_option' => '<Enable/Disable>',
                    'label' => '<Enable/Disable>',
                    'value_options' => array(
                        '0' => 'Disabled',
                        '1' => 'Enabled'
                    )
                ),
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control',
                    'id' => 'instStatusS'
                )
            ));
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
    }

    public function createInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();
        $reqIps = array(
            'name',
            'emailId',
            'mobile',
            'address',
            'enabled'
        );
        foreach ($reqIps as $inputName) {
            $inputFilter->add(
                array(
                    'name' => $inputName,
                    'required' => true
                ));
        }
        
        $inputFilter->add(
            array(
                'name' => 'name',
                'required' => true,
                'validators' => array(
                    new \Zend\I18n\Validator\Alpha(
                        array(
                            'allowWhiteSpace' => true
                        )),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 4,
                            'max' => 180
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
                'name' => 'mobile',
                'required' => true,
                'validators' => array(
                    new \Zend\Validator\Digits(),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 10,
                            'max' => 10
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
                'name' => 'commissionPercentage',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Between',
                        'options' => array(
                            'min' => 0,
                            'max' => 100,
                            'inclusive' => false,
                            'message' => 'The percentage must be between 1 and 100'
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
        return $inputFilter;
    }
}

