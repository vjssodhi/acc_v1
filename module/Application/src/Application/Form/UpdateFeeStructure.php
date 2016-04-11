<?php
namespace Application\Form;

use Zend\InputFilter;
use Zend\Form\Form;

class UpdateFeeStructure extends Form
{

    protected $structOptions;

    public function __construct($structOptions)
    {
        $this->structOptions = $structOptions;
        parent::__construct($name = null, $options = array());
        $this->addElements();
        $this->setInputFilter($this->createInputFilter());
    }

    public function addElements()
    {
        foreach ($this->structOptions as $key => $value) {
            $this->add(array(
                'name' => $key,
                'attributes' => array(
                    'required' => 'required',
                    'class' => 'form-control',
                    'id' => $key . 'I',
                    'placeholder' => 'Set new name for ' . $value['name'] . ', Old name: ' . $value['name']
                ),
                'options' => array(
                    'label' => 'Set new name for ' . $value['name'] . ', Old name: ' . $value['name']
                )
            ));
            $this->add(array(
                'name' => $key . 'amount',
                'options' => array(
                    'label' => 'Set new Amount for ' . $value['name'] . ', Old Value: ' . $value['amount']
                ),
                'attributes' => array(
                    'required' => 'required',
                    'id' => $key . 'amount' . 'I',
                    'placeholder' => 'Set new Amount for ' . $value['name'] . ', Old Value: ' . $value['amount'],
                    'class' => 'form-control'
                )
            ));
            $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => $key . 'action',
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
                    'id' => $key . 'actionS'
                )
            ));
        }
        
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
        
        foreach ($this->structOptions as $key => $value) {
            $inputFilter->add(array(
                'name' => $key,
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'regex',
                        'options' => array(
                            'pattern' => '/(^[a-zA-Z](?:[a-zA-Z0-9]|(?<=[a-zA-Z0-9])[_\\- .](?=[a-zA-Z0-9]))*\\z)/',
                            'message' => 'The name must be alphanumeric.{ Non-Consecutive Word-Separators [ Space,Hypehns(-),Underscore(_) and Dots(.) ]  can be used }'
                        )
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
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
            )
            );
            $inputFilter->add(array(
                'name' => $key . 'amount',
                'required' => true,
                'validators' => array(
                    new \Zend\Validator\Digits(),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 1,
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
        }
        
        return $inputFilter;
    }
}

