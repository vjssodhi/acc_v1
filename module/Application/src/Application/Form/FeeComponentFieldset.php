<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Application\NonOrm\FeeComponent;

class FeeComponentFieldset extends Fieldset implements InputFilterProviderInterface
{

    public function __construct()
    {
        parent::__construct('feeComponent');
        $this->setHydrator(new ClassMethods(false))->setObject(new FeeComponent());
        $this->add(array(
            'name' => 'name',
            'options' => array(
                'label' => 'Name of the Component'
            ),
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'placeholder' => 'Name of the Component'
            )
        ));
        $this->add(array(
            'name' => 'amount',
            'options' => array(
                'label' => 'Amount'
            ),
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Amount',
                'class' => 'form-control'
            )
        ));
    }

    /**
     *
     * @return array
     *
     */
    public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
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
            ),
            'amount' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'digits',
                        'options' => array(
                            'message' => 'the amount must be numeric'
                        )
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 10,
                            'message' => 'the amount can contain maximum of 10 digits'
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
    }
}

