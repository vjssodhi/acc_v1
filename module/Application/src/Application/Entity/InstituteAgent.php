<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="itfi_finmgmt_institute_agent",
 * uniqueConstraints={
 * @ORM\UniqueConstraint(name="inst_agnt_unq",columns={"instituteId","agentId"})
 * }
 * )
 */
class InstituteAgent
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
     *
     * @var Agent
     */
    protected $institute;

    /**
     * @ORM\ManyToOne(targetEntity="Agent", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="agentId", referencedColumnName="id",nullable=false,onDelete="CASCADE")
     *
     * @var Agent
     */
    protected $agent;

    /**
     * @ORM\Column(type="integer",options={"unsigned"=true})
     */
    protected $commissionPercentage;

    /**
     * @ORM\Column(type="boolean", nullable = false)
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
     * @return the $institute
     */
    public function getInstitute()
    {
        return $this->institute;
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
     * @param \Application\Entity\Agent $institute            
     */
    public function setInstitute($institute)
    {
        $this->institute = $institute;
    }

    /**
     *
     * @param \Application\Entity\Agent $agent            
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
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