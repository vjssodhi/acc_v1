<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(
 * name="itfi_finmgmt_agent_payment"
 * )
 */
class AgentPayment
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer",options={"unsigned"=true})
     */
    protected $id;

    /**
     * @ORM\Column(type="string",nullable = false)
     */
    protected $emailId;

    /**
     * @ORM\Column(type="bigint",nullable = false,options={"unsigned"=true})
     */
    protected $totalCommission;

    /**
     * @ORM\Column(type="bigint",nullable = false,options={"unsigned"=true})
     */
    protected $paidAmmount;

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
     * @return the $emailId
     */
    public function getEmailId()
    {
        return $this->emailId;
    }

    /**
     *
     * @return the $totalCommission
     */
    public function getTotalCommission()
    {
        return $this->totalCommission;
    }

    /**
     *
     * @return the $paidAmmount
     */
    public function getPaidAmmount()
    {
        return $this->paidAmmount;
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
     * @param field_type $emailId            
     */
    public function setEmailId($emailId)
    {
        $this->emailId = $emailId;
    }

    /**
     *
     * @param field_type $totalCommission            
     */
    public function setTotalCommission($totalCommission)
    {
        $this->totalCommission = $totalCommission;
    }

    /**
     *
     * @param field_type $paidAmmount            
     */
    public function setPaidAmmount($paidAmmount)
    {
        $this->paidAmmount = $paidAmmount;
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