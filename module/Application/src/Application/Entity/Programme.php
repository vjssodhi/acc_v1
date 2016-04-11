<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(
 * name="itfi_finmgmt_programme",
 * uniqueConstraints={
 * @ORM\UniqueConstraint(name="inst_prog_unq",columns={"instituteId","name"}),
 * @ORM\UniqueConstraint(name="inst_proga_unq",columns={"instituteId","abbreviation"})
 * }
 * )
 */
class Programme
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
     * @ORM\Column(type="string", nullable = true)
     */
    protected $abbreviation;

    /**
     * @ORM\ManyToOne(targetEntity="Institute", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="instituteId", referencedColumnName="id",nullable=false,onDelete="CASCADE")
     *
     * @var Institute
     */
    protected $institute;

    /**
     * @ORM\Column(type="bigint",options={"unsigned"=true})
     */
    protected $feeAmount;

    /**
     * @ORM\Column(type="string")
     */
    protected $feeCurrency;

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
     * @param field_type $name            
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     *
     * @return the $abbreviation
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     *
     * @param field_type $abbreviation            
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;
    }

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
     *
     * @return the $feeAmount
     */
    public function getFeeAmount()
    {
        return $this->feeAmount;
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
     * @return the $feeCurrency
     */
    public function getFeeCurrency()
    {
        return $this->feeCurrency;
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