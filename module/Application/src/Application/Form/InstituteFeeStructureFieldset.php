<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;
use Application\NonOrm\InstituteFeeStructure;

class InstituteFeeStructureFieldset extends Fieldset
{

    public function __construct()
    {
        parent::__construct('instituteFeeStructure');
        $this->setHydrator(new ClassMethods(false))->setObject(new InstituteFeeStructure());
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'feeComponents',
            'options' => array(
                // 'label' => 'Please choose components',
                'count' => 1,
                'should_create_template' => true,
                'allow_add' => true,
                'allow_remove' => true,
                'template_placeholder' => '__placeholder__',
                'target_element' => array(
                    'type' => 'Application\Form\FeeComponentFieldset'
                )
            )
        ));
    }
}

