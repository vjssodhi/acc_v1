<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="itfi_finmgmt_institute_fee_structure",
 * uniqueConstraints={
 * @ORM\UniqueConstraint(name="inst_cmp_name_unq",columns={"instituteId","name"})
 * }
 * )
 */
class InstituteFeeStructure
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Institute", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="instituteId", referencedColumnName="id",nullable=false,onDelete="CASCADE")
     */
    protected $institute;

    /**
     * @ORM\Column(type="string",nullable = false)
     */
    protected $name;

    /**
     * @ORM\Column(type="integer",nullable = false,options={"unsigned"=true})
     */
    protected $amount;

    /**
     * @ORM\Column(type="boolean", nullable = true)
     */
    protected $enabled;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    protected $updatedOn;

    /**
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    protected $createdOn;

    /**
     *
     * @return the $institute
     */
    public function getInstitute()
    {
        return $this->institute;
    }

    /**
     *
     * @param field_type $institute            
     */
    public function setInstitute($institute)
    {
        $this->institute = $institute;
    }

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
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
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
     * @return the $enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
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