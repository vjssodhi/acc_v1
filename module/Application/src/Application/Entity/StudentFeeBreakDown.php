<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(
 * name="itfi_finmgmt_student_fee_breakdown",
 * uniqueConstraints={
 * @ORM\UniqueConstraint(name="inst_stu_cmp_unq",columns={"studentId","componentId"})
 * }
 * )
 */
class StudentFeeBreakDown
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer",options={"unsigned"=true})
     */
    protected $id;

    /**
     * @ORM\Column(type="bigint")
     */
    protected $studentId;

    /**
     * @ORM\Column(type="bigint")
     */
    protected $componentId;

    /**
     * @ORM\Column(type="integer")
     */
    protected $amount;

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
     * @return the $studentId
     */
    public function getStudentId()
    {
        return $this->studentId;
    }

    /**
     *
     * @param field_type $studentId            
     */
    public function setStudentId($studentId)
    {
        $this->studentId = $studentId;
    }

    /**
     *
     * @return the $componentId
     */
    public function getComponentId()
    {
        return $this->componentId;
    }

    /**
     *
     * @param field_type $componentId            
     */
    public function setComponentId($componentId)
    {
        $this->componentId = $componentId;
    }

    /**
     *
     * @return the $amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     *
     * @param field_type $amount            
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
     * @param number $updatedOn            
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
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
     * @param number $createdOn            
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }
}