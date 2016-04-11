<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(
 * name="itfi_finmgmt_student",
 * uniqueConstraints={
 * @ORM\UniqueConstraint(name="inst_stu_unq",columns={"programmeId","id"}),
 * @ORM\UniqueConstraint(name="inst_stu_email_unq",columns={"programmeId","emailId"})
 * }
 * )
 */
class Student
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer",options={"unsigned"=true})
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $emailId;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true}, nullable = true)
     */
    protected $dateOfBirth;

    /**
     * @ORM\Column(type="bigint",nullable = true,options={"unsigned"=true})
     */
    protected $mobile;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $address;

    
     /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $city;
    
     /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $country;
    
     /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $zipcode;



    /**
     * @ORM\ManyToOne(targetEntity="Programme", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="programmeId", referencedColumnName="id",nullable=false,onDelete="CASCADE")
     *
     * @var Programme
     */
    protected $programme;

    /**
     * @ORM\ManyToOne(targetEntity="Agent", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="agentId", referencedColumnName="id",nullable=false,onDelete="CASCADE")
     *
     * @var Agent
     */
    protected $agent;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $gender;

    /**
     * @ORM\Column(type="integer",options={"unsigned"=true},nullable=true)
     */
    protected $feeAmount;

    /**
     * @ORM\Column(type="string",options={"unsigned"=true},nullable=true)
     */
    protected $feeCurrency;

    /**
     * @ORM\Column(type="bigint",options={"unsigned"=true},nullable=true)
     */
    protected $commissionToBePaidByInstitute;

    /**
     * @ORM\Column(type="boolean", nullable = true)
     */
    protected $commissionStatus;

    /**
     * @ORM\Column(type="boolean", nullable = true)
     */
    protected $enabled;

    /**
     * @ORM\Column(type="bigint",options={"unsigned"=true})
     */
    protected $updatedOn;

    /**
     * @ORM\Column(type="bigint",options={"unsigned"=true})
     */
    protected $createdOn;

    /**
     * @ORM\PrePersist
     */
    public function logDatesOnCreate()
    {
        $currentDate = time();
        $this->updatedOn = $currentDate;
        $this->createdOn = $currentDate;
    }

    /**
     * @ORM\PreUpdate
     */
    public function logDatesOnUpdate()
    {
        $this->updatedOn = time();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return the $emailId
     */
    public function getEmailId()
    {
        return $this->emailId;
    }

    /**
     *
     * @return the $dateOfBirth
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     *
     * @return the $mobile
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     *
     * @return the $address
     */
    public function getAddress()
    {
        return $this->address;
    }
  
    /**
     *
     * @return the $city
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /**
     *
     * @return the $country
     */
    public function getCountry()
    {
        return $this->country;
    }
    
    /**
     *
     * @return the $zipcode
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }
    /**
     *
     * @return the $programme
     */
    public function getProgramme()
    {
        return $this->programme;
    }

    /**
     *
     * @return the $agent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     *
     * @return the $feeAmount
     */
    public function getFeeAmount()
    {
        return $this->feeAmount;
    }

    /**
     *
     * @return the $feeCurrency
     */
    public function getFeeCurrency()
    {
        return $this->feeCurrency;
    }

    /**
     *
     * @return the $commissionToBePaidByInstitute
     */
    public function getCommissionToBePaidByInstitute()
    {
        return $this->commissionToBePaidByInstitute;
    }

    /**
     *
     * @return the $commissionStatus
     */
    public function getCommissionStatus()
    {
        return $this->commissionStatus;
    }

    /**
     *
     * @return the $enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     *
     * @return the $updatedOn
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     *
     * @return the $createdOn
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     *
     * @param field_type $name            
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     *
     * @param field_type $emailId            
     */
    public function setEmailId($emailId)
    {
        $this->emailId = $emailId;
    }

    /**
     *
     * @param field_type $dateOfBirth            
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     *
     * @param field_type $mobile            
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     *
     * @param field_type $address            
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     *
     * @param field_type $city            
     */
    public function setCity($city)
    {
        $this->city = $city;
    }
    /**
     *
     * @param field_type $country            
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
    
    /**
     *
     * @param field_type $zipcode            
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }
    /**
     *
     * @param field_type $programme            
     */
    public function setProgramme($programme)
    {
        $this->programme = $programme;
    }

    /**
     *
     * @param field_type $agent            
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    /**
     *
     * @param field_type $feeAmount            
     */
    public function setFeeAmount($feeAmount)
    {
        $this->feeAmount = $feeAmount;
    }

    /**
     *
     * @param field_type $feeCurrency            
     */
    public function setFeeCurrency($feeCurrency)
    {
        $this->feeCurrency = $feeCurrency;
    }

    /**
     *
     * @param field_type $commissionToBePaidByInstitute            
     */
    public function setCommissionToBePaidByInstitute($commissionToBePaidByInstitute)
    {
        $this->commissionToBePaidByInstitute = $commissionToBePaidByInstitute;
    }

    /**
     *
     * @param field_type $commissionStatus            
     */
    public function setCommissionStatus($commissionStatus)
    {
        $this->commissionStatus = $commissionStatus;
    }

    /**
     *
     * @param field_type $enabled            
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     *
     * @param number $updatedOn            
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    /**
     *
     * @param number $createdOn            
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     *
     * @return the $gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     *
     * @param field_type $gender            
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }
}