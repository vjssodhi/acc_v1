<?php
namespace Application\Form;

use Zend\InputFilter;
use Zend\Form\Form;

class AssignAgent extends Form
{

    protected $agentOptions;

    public function __construct($agentOptions)
    {
        $this->agentOptions = $agentOptions;
        parent::__construct($name = null, $options = array());
        $this->addElements();
        $this->setInputFilter($this->createInputFilter());
        
         $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
       $result= $connection->exec("SELECT agentId FROM itfi_finmgmt_institute_agent WHERE instituteId='".$instituteId."'");
       
       $connection->commit();
    }

    public function addElements()
    {
        $this->add(array(
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
        $this->add(array(
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
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'agentId',
            'options' => array(
                'empty_option' => '<Select Agent>',
                'label' => '<Select Agent>',
                'value_options' => $this->agentOptions
            ),
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'id' => 'agentIdS'
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
            'enabled',
            'agentId'
        );
        foreach ($reqIps as $inputName) {
            $inputFilter->add(array(
                'name' => $inputName,
                'required' => true
            ));
        }
        
        $inputFilter->add(array(
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

