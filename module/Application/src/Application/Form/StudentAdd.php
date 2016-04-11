<?php
namespace Application\Form;

use Zend\InputFilter;
use Zend\Form\Form;
use Application\Utilities\Misc;

class StudentAdd extends Form
{

    protected $programmes;

    protected $structOptions;

    public function __construct($programmes, $structOptions, $name = null, $options = array())
    {
        $this->structOptions = $structOptions;
        $this->programmes = $programmes;
        parent::__construct($name, $options);
        $this->addElements();
        $this->setInputFilter($this->createInputFilter());
    }

    public function addElements()
    {
        $this->add(array(
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
        $this->add(array(
            'name' => 'emailId',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Email-Id',
                'class' => 'form-control',
                'id' => 'emailIdI'
            ),
            'options' => array(
                'label' => 'Student\'s Email-Id'
            )
        ));
        $this->add(array(
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
        foreach ($this->structOptions as $key => $vl) {
            $this->add(array(
                'name' => $key,
                'attributes' => array(
                    'class' => 'form-control',
                    'id' => $key . 'I',
                    'placeholder' => $vl['name'] . ',Default Amount: ' . $vl['amount']
                ),
                'options' => array(
                    'label' => $vl['name'] . ',Default Amount: ' . $vl['amount']
                )
            ));
        }
        $this->add(array(
            'name' => 'addCommission',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'addCommissionI',
                'placeholder' => 'Amount to Add to Default Commission'
            ),
            'options' => array(
                'label' => 'Amount to Add to Default Commission'
            )
        ));
        // $this->add(array(
        // 'name' => 'feeDiscountPercentage',
        // 'attributes' => array(
        // 'class' => 'form-control',
        // 'id' => 'feeDiscountPercentageI',
        // 'placeholder' => 'Discount on Tuition Fee in Percentage'
        // ),
        // 'options' => array(
        // 'label' => 'Discount on Tuition Fee in Percentage'
        // )
        // ));
        $this->add(array(
            'name' => 'tuitionFees',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'tuitionFeesI',
                'placeholder' => 'Tuition Fees To be Paid'
            ),
            'options' => array(
                'label' => 'Tuition Fees To be Paid'
            )
        ));
        $this->add(array(
            'name' => 'deductCommission',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'deductCommissionI',
                'placeholder' => 'Amount to Deduct from Default Commission'
            ),
            'options' => array(
                'label' => 'Amount to Deduct from Default Commission'
            )
        ));
        $this->add(array(
            'name' => 'address',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Student\'s Address',
                'class' => 'form-control',
                'id' => 'addressI'
            ),
            'options' => array(
                'label' => 'Student\'s Address'
            )
        ));
        
        $this->add(array(
            'name' => 'city',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Student\'s City',
                'class' => 'form-control',
                'id' => 'city'
            ),
            'options' => array(
                'label' => 'City'
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
        
         $this->add(array(
            'name' => 'zipcode',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Zipcode',
                'class' => 'form-control',
                'id' => 'zipcodeI'
            ),
            'options' => array(
                'label' => 'zipcode'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'commissionStatus',
            'options' => array(
                'empty_option' => '<Set Commission Status>',
                'label' => 'Set Commission Status',
                'value_options' => array(
                    '1' => 'Paid',
                    '0' => 'Pending'
                )
            ),
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'id' => 'commissionStatusS'
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'gender',
            'options' => array(
                'empty_option' => '<Select Gender>',
                'label' => 'Gender',
                'value_options' => array(
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'other' => 'Other'
                )
            ),
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control genderSelect',
                'id' => 'bioGenderS'
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'programmeId',
            'options' => array(
                'empty_option' => '<Select Programme>',
                'label' => 'Select Programme',
                'value_options' => $this->programmes
            ),
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'id' => 'programmeIdS'
            )
        ));
        $dates = array();
        for ($i = 1; $i <= 31; $i = $i + 1) {
            $dates[$i] = $i;
        }
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'birthDay',
            'options' => array(
                'empty_option' => '<Date>',
                'label' => '',
                'value_options' => $dates
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'birthDayS',
                'required' => 'required'
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'birthMonth',
            'options' => array(
                'empty_option' => '<Month>',
                'label' => '',
                'value_options' => array(
                    '01' => 'Jan',
                    '02' => 'February',
                    '03' => 'March',
                    '04' => 'April',
                    '05' => 'May',
                    '06' => 'June',
                    '07' => 'July',
                    '08' => 'August',
                    '09' => 'September',
                    '10' => 'October',
                    '11' => 'November',
                    '12' => 'December'
                )
            ),
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'id' => 'birthMonthS'
            )
        ));
        $this->add(array(
            'name' => 'birthYear',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
                'id' => 'birthYearI',
                'placeholder' => 'Year'
            ),
            'options' => array(
                'label' => ''
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
            'name',
            'emailId',
            'mobile',
            'address',
            'city',
            'zipcode',
            'birthMonth',
            'commissionStatus'
        );
        foreach ($reqIps as $inputName) {
            $inputFilter->add(array(
                'name' => $inputName,
                'required' => true
            ));
        }
        $comms = array(
            'addCommission' => false,
            'deductCommission' => false
        );
        foreach ($comms as $fld => $req) {
            $inputFilter->add(array(
                'name' => $fld,
                'required' => $req,
                'validators' => array(
                    new \Zend\Validator\Digits(),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 2,
                            'max' => 8
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
        $inputFilter->add(array(
            'name' => 'name',
            'required' => true,
            'validators' => array(
                new \Zend\I18n\Validator\Alpha(array(
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
        foreach ($this->structOptions as $key => $vl) {
            $inputFilter->add(array(
                'name' => $key,
                'required' => false,
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
        $inputFilter->add(array(
            'name' => 'mobile',
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
        $inputFilter->add(array(
            'name' => 'birthYear',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Between',
                    'options' => array(
                        'min' => 1900,
                        'max' => 2999,
                        'inclusive' => false,
                        'message' => 'Please specify a valid year'
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
        $inputFilter->add(array(
            'name' => 'tuitionFees',
            'required' => false,
            'validators' => array(
                array(
                    'name' => 'Between',
                    'options' => array(
                        'min' => 0,
                        'max' => 1000000,
                        'inclusive' => false,
                        'message' => 'Incorrect Fee Amount'
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
        // $inputFilter->add(array(
        // 'name' => 'feeDiscountPercentage',
        // 'required' => false,
        // 'validators' => array(
        // array(
        // 'name' => 'Between',
        // 'options' => array(
        // 'min' => 0,
        // 'max' => 100,
        // 'inclusive' => false,
        // 'message' => 'Fee Discount must be between 1 and 100'
        // )
        // )
        // ),
        // 'filters' => array(
        // array(
        // 'name' => 'StripTags'
        // ),
        // array(
        // 'name' => 'StringTrim'
        // )
        // )
        // ));
        return $inputFilter;
    }
}

