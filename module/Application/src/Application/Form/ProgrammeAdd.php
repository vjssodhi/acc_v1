<?php
namespace Application\Form;

use Zend\InputFilter;
use Zend\Form\Form;
use Application\Utilities\Misc;

class ProgrammeAdd extends Form
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
                    'placeholder' => 'Name of Programme',
                    'class' => 'form-control',
                    'id' => 'nameI'
                ),
                'options' => array(
                    'label' => 'Name'
                )
            ));
        $this->add(
            array(
                'name' => 'abbreviation',
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
                    'placeholder' => 'Abbreviation',
                    'class' => 'form-control',
                    'id' => 'abbreviationI'
                ),
                'options' => array(
                    'label' => 'Abbreviation'
                )
            ));
        $this->add(
            array(
                'name' => 'feeAmount',
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control',
                    'id' => 'mobileI',
                    'placeholder' => 'Fees for this Course'
                ),
                'options' => array(
                    'label' => ''
                )
            ));
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'feeCurrency',
                'options' => array(
                    'empty_option' => '<Select Currency>',
                    'label' => '<Select Currency>',
                    'value_options' => Misc::getCurrencycodes()
                ),
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control',
                    'id' => 'feeCurrencyS'
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
            'enabled',
            'feeAmount',
            'feeCurrency'
        );
        
        foreach ($reqIps as $inputName) {
            $inputFilter->add(
                array(
                    'name' => $inputName,
                    'required' => true
                ));
        }
        $names = array(
            'name' => array(
                'allowWhiteSpace' => true,
                'required' => true
            ),
            'abbreviation' => array(
                'allowWhiteSpace' => true,
                'required' => false
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
                                'min' => 2,
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
        
        return $inputFilter;
    }
}

