<?php
namespace Application\NonOrm;

class InstituteFeeStructure
{

    protected $instituteId;

    /**
     *
     * @var array
     */
    protected $feeComponents;

    /**
     *
     * @return the $instituteId
     */
    public function getInstituteId()
    {
        return $this->instituteId;
    }

    /**
     *
     * @param field_type $instituteId            
     */
    public function setInstituteId($instituteId)
    {
        $this->instituteId = $instituteId;
    }

    /**
     *
     * @return the $feeComponents
     */
    public function getFeeComponents()
    {
        return $this->feeComponents;
    }

    /**
     *
     * @param multitype: $feeComponents            
     */
    public function setFeeComponents($feeComponents)
    {
        $this->feeComponents = $feeComponents;
    }
}