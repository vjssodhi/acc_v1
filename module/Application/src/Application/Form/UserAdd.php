<?php
namespace Application\Form;

use Zend\InputFilter;
use Zend\Form\Form;
use Zend\Validator\Identical;
use Application\Utilities\Misc;

class UserAdd extends Form
{

    private $displayAllFields;

    public function __construct($name = null, $options = array(), $displayAllFields = false)
    {
        $this->displayAllFields = $displayAllFields;
        parent::__construct($name, $options);
        $this->addElements();
        $this->setInputFilter($this->createInputFilter());
    }

    public function addElements()
    {
        $this->add(
            array(
                'name' => 'fullName',
                'type' => 'Zend\Form\Element\Text',
                'attributes' => array(
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
                'name' => 'loginId',
                'attributes' => array(
                    'placeholder' => 'Login Id for User',
                    'class' => 'form-control',
                    'id' => 'loginIdI'
                ),
                'options' => array(
                    'label' => 'Login ID'
                )
            ));
        $dates = array();
        for ($i = 1; $i <= 31; $i = $i + 1) {
            $dates[$i] = $i;
        }
        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'birthDay',
                'options' => array(
                    'empty_option' => '<Date>',
                    'label' => '',
                    'value_options' => $dates
                ),
                'attributes' => array(
                    'class' => 'form-control',
                    'id' => 'birthDayS'
                )
            ));
        
        $this->add(
            array(
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
        $this->add(
            array(
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
        $this->add(
            
            array(
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
                    'class' => 'form-control',
                    'id' => 'bioGenderS'
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
        if ($this->displayAllFields) {
            $this->add(
                array(
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'marritalStatus',
                    'options' => array(
                        'empty_option' => '<Your Marrital Status>',
                        'label' => 'Marrital Status',
                        'value_options' => array(
                            'Single' => 'Single',
                            'Married' => 'Married'
                        )
                    ),
                    'attributes' => array(
                        'required' => 'required',
                        'class' => 'form-control',
                        'id' => 'marritalStatusS'
                    )
                ));
            
            $this->add(
                array(
                    'name' => 'nationality',
                    'attributes' => array(
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
                    'attributes' => array(
                        'class' => 'form-control'
                    ),
                    'type' => 'Zend\Form\Element\Select',
                    'name' => 'state',
                    'options' => array(
                        'empty_option' => '<State>',
                        'label' => 'State',
                        'value_options' => array(
                            "Andaman and Nicobar Islands" => 'Andaman and Nicobar Islands',
                            "Andhra Pradesh" => 'Andhra Pradesh',
                            "Arunachal Pradesh" => 'Arunachal Pradesh',
                            "Assam" => 'Assam',
                            "Bihar" => 'Bihar',
                            "Chandigarh" => 'Chandigarh',
                            "Chhattisgarh" => 'Chhattisgarh',
                            "Dadra and Nagar Haveli" => 'Dadra and Nagar Haveli',
                            "Daman and Diu" => 'Daman and Diu',
                            "Delhi" => 'Delhi',
                            "Goa" => 'Goa',
                            "Gujarat" => 'Gujarat',
                            "Haryana" => 'Haryana',
                            "Himachal Pradesh" => 'Himachal Pradesh',
                            "Jammu and Kashmir" => 'Jammu and Kashmir',
                            "Jharkhand" => 'Jharkhand',
                            "Karnataka" => 'Karnataka',
                            "Kerala" => 'Kerala',
                            "Lakshadweep" => 'Lakshadweep',
                            "Madhya Pradesh" => 'Madhya Pradesh',
                            "Maharashtra" => 'Maharashtra',
                            "Manipur" => 'Manipur',
                            "Meghalaya" => 'Meghalaya',
                            "Mizoram" => 'Mizoram',
                            "Nagaland" => 'Nagaland',
                            "Orissa" => 'Orissa',
                            "Pondicherry" => 'Pondicherry',
                            "Punjab" => 'Punjab',
                            "Rajasthan" => 'Rajasthan',
                            "Sikkim" => 'Sikkim',
                            "Tamil Nadu" => 'Tamil Nadu',
                            "Tripura" => 'Tripura',
                            "Uttaranchal" => 'Uttaranchal',
                            "Uttar Pradesh" => 'Uttar Pradesh',
                            "West Bengal" => 'West Bengal'
                        )
                    )
                ));
            $this->add(
                array(
                    
                    'name' => 'city',
                    'type' => 'Zend\Form\Element\Text',
                    'attributes' => array(
                        'placeholder' => 'City',
                        'class' => 'form-control',
                        'id' => 'cityI'
                    ),
                    'options' => array(
                        'label' => 'City '
                    )
                ));
            $this->add(
                array(
                    'name' => 'district',
                    'type' => 'Zend\Form\Element\Text',
                    'attributes' => array(
                        'placeholder' => 'District',
                        'class' => 'form-control',
                        'id' => 'districtI'
                    ),
                    'options' => array(
                        'label' => 'District'
                    )
                ));
            $this->add(
                array(
                    'name' => 'pincode',
                    'attributes' => array(
                        'class' => 'form-control',
                        'id' => 'pincodeI',
                        'placeholder' => 'Pincode'
                    ),
                    'options' => array(
                        'label' => 'Pin Code'
                    )
                ));
        }
        
        $this->add(
            array(
                'name' => 'personalEmailId',
                'type' => 'Zend\Form\Element\Email',
                'attributes' => array(
                    'placeholder' => 'Email-Id',
                    'class' => 'form-control',
                    'id' => 'personalEmailIdI'
                ),
                'options' => array(
                    'label' => 'Your Email-Id'
                )
            ));
        
        $this->add(
            array(
                'name' => 'password',
                'type' => 'Zend\Form\Element\Password',
                'attributes' => array(
                    'class' => 'form-control',
                    'id' => 'passwordI',
                    'placeholder' => 'Set Password'
                ),
                'options' => array(
                    'label' => 'Set Password'
                )
            ));
        $this->add(
            array(
                'name' => 'passwordConfirm',
                'type' => 'Zend\Form\Element\Password',
                'attributes' => array(
                    'class' => 'form-control',
                    'id' => 'passwordConfirmI',
                    'placeholder' => 'Confirm Password or Passphrase'
                ),
                'options' => array(
                    'label' => 'Confirm Password'
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
        if ($this->displayAllFields) {
            $reqIps = array(
                'gender',
                'birthDay',
                'birthMonth',
                'marritalStatus',
                'state',
                'personalEmailId'
            );
            $names = array(
                'fullName' => array(
                    'type' => 'Alpha',
                    'allowWhiteSpace' => true,
                    'required' => true
                ),
                'city' => array(
                    'type' => 'Alpha',
                    'allowWhiteSpace' => true,
                    'required' => true
                ),
                'district' => array(
                    'type' => 'Alpha',
                    'allowWhiteSpace' => true,
                    'required' => true
                )
            );
        } else {
            $reqIps = array(
                'gender',
                'birthDay',
                'birthMonth',
                'personalEmailId'
            );
            $names = array(
                'fullName' => array(
                    'type' => 'Alpha',
                    'allowWhiteSpace' => true,
                    'required' => true
                )
            );
        }
        foreach ($reqIps as $inputName) {
            $inputFilter->add(
                array(
                    'name' => $inputName,
                    'required' => true
                ));
        }
        
        foreach ($names as $name => $reqs) {
            $inputFilter->add(
                array(
                    'name' => $name,
                    'required' => $reqs['required'],
                    'validators' => array(
                        new \Zend\I18n\Validator\Alpha(
                            array(
                                'allowWhiteSpace' => $reqs['allowWhiteSpace']
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
        }
        $inputFilter->add(
            array(
                'name' => 'loginId',
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
                'name' => 'password',
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
                            'min' => 6,
                            'max' => 200
                        )
                    )
                )
            ));
        $inputFilter->add(
            array(
                'name' => 'passwordConfirm',
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
                            'min' => 6,
                            'max' => 200
                        )
                    ),
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'password',
                            'messages' => array(
                                Identical::NOT_SAME => '"Hey bud, your passwords do not match, something ain\'t right!"'
                            )
                        )
                    )
                )
            ));
        
        $years = array(
            'birthYear' => true
        );
        foreach ($years as $year => $req) {
            $inputFilter->add(
                array(
                    'name' => $year,
                    'required' => $req,
                    'validators' => array(
                        new \Zend\Validator\Digits(),
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'min' => 4,
                                'max' => 4
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

