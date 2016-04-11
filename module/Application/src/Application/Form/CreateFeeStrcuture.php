<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class CreateFeeStrcuture extends Form
{

    protected $institutes;

    public function __construct($institutes)
    {
        $this->institutes = $institutes;
        parent::__construct('createFeeStructure');
        $this->setAttribute('method', 'post')
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setInputFilter(new InputFilter());
        $this->add(array(
            
            'type' => 'Zend\Form\Element\Select',
            'name' => 'instituteId',
            'options' => array(
                'empty_option' => '<Select Institute>',
                'label' => 'Select Institute',
                'value_options' => $this->institutes
            ),
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'id' => 'instituteIdS'
            )
        ));
        $this->add(array(
            'type' => 'Application\Form\InstituteFeeStructureFieldset',
            'options' => array(
                'use_as_base_fieldset' => true,
                'institutes' => $this->institutes
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'mcsrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => CSRF_TIMEOUT_SECONDS
                )
            )
        ));
    }
}