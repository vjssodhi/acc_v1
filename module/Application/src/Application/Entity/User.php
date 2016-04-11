<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Model\Interfaces\UserInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="itfi_finmgmt_user")
 */
class User implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    protected $id;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $fullName;

    /**
     * @ORM\Column(type="string",unique=true, nullable = false)
     */
    protected $loginId;

    /**
     * @ORM\Column(type="integer",nullable = false)
     */
    protected $accessLevel;

    /**
     * @ORM\Column(type="string",nullable = false)
     */
    protected $password;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $personalEmailId;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true}, nullable = true)
     */
    protected $dateOfBirth;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $gender;

    /**
     * @ORM\Column(type="bigint",nullable = true,options={"unsigned"=true})
     */
    protected $mobile;

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    protected $marritalStatus;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $nationality;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $state;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $city;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $district;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true},nullable = true)
     */
    protected $pincode;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $permanentAddress;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $correspondenceAddress;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $imageId;

    /**
     * @ORM\Column(type="boolean", nullable = false)
     */
    protected $basicBioComplete;

    /**
     * @ORM\Column(type="boolean", nullable = true)
     */
    protected $accountVerified;

    /**
     * @ORM\Column(type="boolean", nullable = true)
     */
    protected $emailVerified;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    protected $updatedOn;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    protected $createdOn;

    /**
     * @ORM\PrePersist
     */
    public function logDatesOnCreate()
    {
        $currentTimestamp = time();
        $this->updatedOn = $currentTimestamp;
        $this->createdOn = $currentTimestamp;
    }

    /**
     * @ORM\PreUpdate
     */
    public function logDatesOnUpdate()
    {
        $currentTimestamp = time();
        $this->updatedOn = $currentTimestamp;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return the $fullName
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     *
     * @return the $loginId
     */
    public function getLoginId()
    {
        return $this->loginId;
    }

    /**
     *
     * @return the $accessLevel
     */
    public function getAccessLevel()
    {
        return $this->accessLevel;
    }

    /**
     *
     * @return the $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     *
     * @return the $personalEmailId
     */
    public function getPersonalEmailId()
    {
        return $this->personalEmailId;
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
     * @return the $gender
     */
    public function getGender()
    {
        return $this->gender;
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
     * @return the $marritalStatus
     */
    public function getMarritalStatus()
    {
        return $this->marritalStatus;
    }

    /**
     *
     * @return the $nationality
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     *
     * @return the $state
     */
    public function getState()
    {
        return $this->state;
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
     * @return the $district
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     *
     * @return the $pincode
     */
    public function getPincode()
    {
        return $this->pincode;
    }

    /**
     *
     * @return the $permanentAddress
     */
    public function getPermanentAddress()
    {
        return $this->permanentAddress;
    }

    /**
     *
     * @return the $correspondenceAddress
     */
    public function getCorrespondenceAddress()
    {
        return $this->correspondenceAddress;
    }

    /**
     *
     * @return the $imageId
     */
    public function getImageId()
    {
        return $this->imageId;
    }

    /**
     *
     * @return the $basicBioComplete
     */
    public function getBasicBioComplete()
    {
        return $this->basicBioComplete;
    }

    /**
     *
     * @return the $accountVerified
     */
    public function getAccountVerified()
    {
        return $this->accountVerified;
    }

    /**
     *
     * @return the $emailVerified
     */
    public function getEmailVerified()
    {
        return $this->emailVerified;
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
     * @param field_type $fullName            
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     *
     * @param field_type $loginId            
     */
    public function setLoginId($loginId)
    {
        $this->loginId = $loginId;
    }

    /**
     *
     * @param field_type $accessLevel            
     */
    public function setAccessLevel($accessLevel)
    {
        $this->accessLevel = $accessLevel;
    }

    /**
     *
     * @param field_type $password            
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     *
     * @param field_type $personalEmailId            
     */
    public function setPersonalEmailId($personalEmailId)
    {
        $this->personalEmailId = $personalEmailId;
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
     * @param field_type $gender            
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
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
     * @param field_type $marritalStatus            
     */
    public function setMarritalStatus($marritalStatus)
    {
        $this->marritalStatus = $marritalStatus;
    }

    /**
     *
     * @param field_type $nationality            
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;
    }

    /**
     *
     * @param field_type $state            
     */
    public function setState($state)
    {
        $this->state = $state;
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
     * @param field_type $district            
     */
    public function setDistrict($district)
    {
        $this->district = $district;
    }

    /**
     *
     * @param field_type $pincode            
     */
    public function setPincode($pincode)
    {
        $this->pincode = $pincode;
    }

    /**
     *
     * @param field_type $permanentAddress            
     */
    public function setPermanentAddress($permanentAddress)
    {
        $this->permanentAddress = $permanentAddress;
    }

    /**
     *
     * @param field_type $correspondenceAddress            
     */
    public function setCorrespondenceAddress($correspondenceAddress)
    {
        $this->correspondenceAddress = $correspondenceAddress;
    }

    /**
     *
     * @param field_type $imageId            
     */
    public function setImageId($imageId)
    {
        $this->imageId = $imageId;
    }

    /**
     *
     * @param field_type $basicBioComplete            
     */
    public function setBasicBioComplete($basicBioComplete)
    {
        $this->basicBioComplete = $basicBioComplete;
    }

    /**
     *
     * @param field_type $accountVerified            
     */
    public function setAccountVerified($accountVerified)
    {
        $this->accountVerified = $accountVerified;
    }

    /**
     *
     * @param field_type $emailVerified            
     */
    public function setEmailVerified($emailVerified)
    {
        $this->emailVerified = $emailVerified;
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
}