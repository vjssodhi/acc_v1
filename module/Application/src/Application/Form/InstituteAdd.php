<?php
namespace Application\Form;

use Zend\InputFilter;
use Zend\Form\Form;
use Application\Utilities\Misc;

class InstituteAdd extends Form
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
                    'placeholder' => 'Name of Institute',
                    'class' => 'form-control',
                    'id' => 'nameI'
                ),
                'options' => array(
                    'label' => 'Name'
                )
            ));
         $this->add(
            array(
                'name' => 'institudeAutoId',
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                    'required' => 'required',
                    'placeholder' => 'Id of Institute',
                    'class' => 'form-control',
                    'id' => 'institudeAutoId'
                ),
                'options' => array(
                    'label' => 'Institute Id'
                )
            ));
        $this->add(
            array(
                'name' => 'emailId',
                'type' => 'Zend\Form\Element\Email',
                'attributes' => array(
                    'required' => 'required',
                    'placeholder' => 'Institute\'s Email-Id',
                    'class' => 'form-control',
                    'id' => 'instituteEmailIdI'
                ),
                'options' => array(
                    'label' => 'Institute\'s Email-Id'
                )
            ));
        $this->add(
            array(
                'name' => 'emailIdTwo',
                'type' => 'Zend\Form\Element\Email',
                'attributes' => array(
                    'placeholder' => 'Institute\'s Alternate Email-Id',
                    'class' => 'form-control',
                    'id' => 'instituteEmailIdI2'
                ),
                'options' => array(
                    'label' => 'Institute\'s Alternate Email-Id'
                )
            ));
        $this->add(
            array(
                'name' => 'phoneNumber',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control',
                    'id' => 'mobileI',
                    'placeholder' => '9 to 11 Digit Contact Number'
                ),
                'options' => array(
                    'label' => ''
                )
            ));
        $this->add(
            array(
                'name' => 'phoneNumberThree',
                'attributes' => array(
                    'class' => 'form-control',
                    'id' => 'mobileI3',
                    'placeholder' => 'Additional Contact Number(Optional)'
                ),
                'options' => array(
                    'label' => ''
                )
            ));
        $this->add(
            array(
                'name' => 'phoneNumberTwo',
                'attributes' => array(
                    'class' => 'form-control',
                    'id' => 'mobileI2',
                    'placeholder' => 'Additional Contact Number(Optional)'
                ),
                'options' => array(
                    'label' => ''
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
                'name' => 'pincode',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control',
                    'id' => 'pincodeI',
                    'placeholder' => 'Pincode/ZipCode'
                ),
                'options' => array(
                    'label' => 'Pin Code/ZipCode'
                )
            ));
        $this->add(
            array(
                'name' => 'country',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control'
                ),
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'empty_option' => '<Country>',
                    'label' => 'Country',
                    'value_options' => Misc::getCountries()
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
            'institudeAutoId',
            'emailId',
            'phoneNumber',
            'enabled',
            'country',
            'pincode'
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
                'name' => 'emailIdTwo',
                'required' => false
            ));
        $names = array(
            'name' => array(
                'type' => 'Alpha',
                'allowWhiteSpace' => true,
                'required' => true
            )
        );
        foreach ($names as $name => $specs) {
            $inputFilter->add(
                array(
                    'name' => $name,
                    'required' => $specs['required'],
                    'validators' => array(
                        new \Zend\I18n\Validator\Alpha(
                            array(
                                'allowWhiteSpace' => $specs['allowWhiteSpace']
                            )),
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'min' => 4,
                                'max' => 250
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
        }
        $inputFilter->add(
            array(
                'name' => 'phoneNumber',
                'required' => true,
                'validators' => array(
                    new \Zend\Validator\Digits(),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 9,
                            'max' => 11
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

