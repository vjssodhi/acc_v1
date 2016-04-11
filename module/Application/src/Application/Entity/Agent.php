<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(
 * name="itfi_finmgmt_agent",
 * uniqueConstraints={
 * @ORM\UniqueConstraint(name="inst_agnt_mbl_unq",columns={"mobile"}),
 * @ORM\UniqueConstraint(name="inst_agnt_email_unq",columns={"emailId"})
 * }
 * )
 */
class Agent
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
     * @ORM\Column(type="bigint",nullable = true,options={"unsigned"=true})
     */
    protected $mobile;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    protected $address;

    /**
     * @ORM\Column(type="integer",options={"unsigned"=true})
     */
    protected $commissionPercentage;

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
     * @return the $commissionPercentage
     */
    public function getCommissionPercentage()
    {
        return $this->commissionPercentage;
    }

    /**
     *
     * @param field_type $commissionPercentage            
     */
    public function setCommissionPercentage($commissionPercentage)
    {
        $this->commissionPercentage = $commissionPercentage;
    }
}