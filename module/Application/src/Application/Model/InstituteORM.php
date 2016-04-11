<?php
namespace Application\Model;

use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Traversable;
use Zend\Stdlib\ArrayUtils;
use Application\Entity\Institute;
use Application\Utilities\NumberPlay;
use Application\Entity\InstituteFeeStructure;

class InstituteORM extends CommonFetch
{

    /**
     *
     * @var AgentORM
     */
    protected $agentModel;

    /**
     *
     * @return the $agentModel
     */
    public function getAgentModel()
    {
        return $this->agentModel;
    }

    /**
     *
     * @param \Application\Model\AgentORM $agentModel            
     */
    public function setAgentModel($agentModel)
    {
        $this->agentModel = $agentModel;
    }

    public function update($id, $data)
    {
        $data = NumberPlay::cleaner($data);
        $problem = false;
        $errors = array();
        $errors['emailId'] = array();
        $errors['emailIdTwo'] = array();
        $errors['phoneNumber'] = array();
        $errors['phoneNumberTwo'] = array();
        $errors['phoneNumberThree'] = array();
        $oldEmailId = $data['emailId'];
        if ($data instanceof \Traversable) {
            $data = ArrayUtils::iteratorToArray($data);
        }
        if (is_object($data)) {
            $data = (array) $data;
        }
        if (! is_array($data)) {
            throw new \InvalidArgumentException(sprintf('Invalid data provided to %s; must be an array or Traversable', __METHOD__));
        }
        $institute = $this->fetchAllInstitutes(array(
            'id' => $id
        ), null, true)[0];
        if (empty($institute)) {
            return false;
        }
        /* @var $institute Institute */
        $oldEmailId = $institute->getEmailId();
        if (! empty($data['emailId']) && ($data['emailId'] !== $oldEmailId)) {
            $instituteXT = $this->fetchAllInstitutes(array(
                'emailId' => $data['emailId']
            ))[0];
            $instituteXT222 = $this->fetchAllInstitutes(array(
                'emailIdTwo' => $data['emailId']
            ))[0];
            if (! empty($instituteXT) || ! empty($instituteXT222)) {
                $errors['emailId'][] = sprintf('%s is already registered', $data['emailId']);
                $problem = true;
            } else {
                $institute->setEmailId($data['emailId']);
            }
        }
        // ************Email id 2***************************//
        $oldEmailId2 = $institute->getEmailIdTwo();
        if (! empty($data['emailIdTwo']) && ($data['emailIdTwo'] !== $oldEmailId2)) {
            $instituteXT22 = $this->fetchAllInstitutes(array(
                'emailIdTwo' => $data['emailIdTwo']
            ))[0];
            $instituteXT444 = $this->fetchAllInstitutes(array(
                'emailId' => $data['emailIdTwo']
            ))[0];
            if (! empty($instituteXT22) || ! empty($instituteXT444)) {
                $errors['emailIdTwo'][] = sprintf('%s is already registered', $data['emailIdTwo']);
                $problem = true;
            } else {
                $institute->setEmailIdTwo($data['emailIdTwo']);
            }
        }
        // //////////////////////////////////////////////////
        $oldphoneNumber = $institute->getPhoneNumber();
        if (! empty($data['phoneNumber']) && ($data['phoneNumber'] !== $oldphoneNumber)) {
            $instituteXDT = $this->fetchAllInstitutes(array(
                'phoneNumber' => $data['phoneNumber']
            ))[0];
            $instituteXDT1234 = $this->fetchAllInstitutes(array(
                'phoneNumberTwo' => $data['phoneNumber']
            ))[0];
            $instituteXDT12345 = $this->fetchAllInstitutes(array(
                'phoneNumberThree' => $data['phoneNumber']
            ))[0];
            if (! empty($instituteXDT) || ! empty($instituteXDT1234) || ! empty($instituteXDT12345)) {
                $errors['phoneNumber'][] = sprintf('%s is already registered', $data['phoneNumber']);
                $problem = true;
            } else {
                $institute->setPhoneNumber($data['phoneNumber']);
            }
        }
        // ************Phone 2***************************//
        $oldPh2 = $institute->getPhoneNumberTwo();
        if (! empty($data['phoneNumberTwo']) && ($data['phoneNumberTwo'] !== $oldPh2)) {
            $institute2XDT = $this->fetchAllInstitutes(array(
                'phoneNumber' => $data['phoneNumberTwo']
            ))[0];
            $instituteXDT2234 = $this->fetchAllInstitutes(array(
                'phoneNumberTwo' => $data['phoneNumberTwo']
            ))[0];
            $instituteXDT22345 = $this->fetchAllInstitutes(array(
                'phoneNumberThree' => $data['phoneNumberTwo']
            ))[0];
            if (! empty($institute2XDT) || ! empty($instituteXDT2234) || ! empty($instituteXDT22345)) {
                $errors['phoneNumberTwo'][] = sprintf('%s is already registered', $data['phoneNumberTwo']);
                $problem = true;
            } else {
                $institute->setPhoneNumberTwo($data['phoneNumberTwo']);
            }
        }
        // ************Phone 3***************************//
        $oldPh3 = $institute->getPhoneNumberThree();
        if (! empty($data['phoneNumberThree']) && ($data['phoneNumberThree'] !== $oldPh3)) {
            $institute3XDT = $this->fetchAllInstitutes(array(
                'phoneNumber' => $data['phoneNumberThree']
            ))[0];
            $instituteXDT3234 = $this->fetchAllInstitutes(array(
                'phoneNumberTwo' => $data['phoneNumberThree']
            ))[0];
            $instituteXDT32345 = $this->fetchAllInstitutes(array(
                'phoneNumberThree' => $data['phoneNumberThree']
            ))[0];
            if (! empty($institute3XDT) || ! empty($instituteXDT3234) || ! empty($instituteXDT32345)) {
                $errors['phoneNumberThree'][] = sprintf('%s is already registered', $data['phoneNumberThree']);
                $problem = true;
            } else {
                $institute->setPhoneNumberThree($data['phoneNumberThree']);
            }
        }
        if ($problem) {
            return $errors;
        }
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            $om->persist($institute);
            $om->flush($institute);
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $om->close();
            throw $e;
        }
        return $institute->getId();
    }

    public function updateFeeStructure($id, $data)
    {
        $feeStructure = $this->fetchStructures(array(
            'id' => $id
        ), null, true)[0];
        if (empty($feeStructure)) {
            return;
        }
        $institute = $this->fetchAllInstitutes(array(
            'id' => $data['instituteId']
        ), null, true)[0];
        if (empty($institute)) {
            return;
        }
        /* @var $feeStructure InstituteFeeStructure */
        $feeStructure->setAmount($data['amount']);
        $feeStructure->setEnabled($data['enabled']);
        $feeStructure->setName($data['name']);
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            $om->flush($feeStructure);
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $om->close();
            throw $e;
        }
    }

    public function saveFeeStructure($data)
    {
       
        $problem = false;
        $requiredFields = array(
            'name' => true,
            'instituteId' => true,
            'amount' => true
        );
        $errors = array();
        $errors['name'] = array();
        $errors['instituteId'] = array();
        $errors['amount'] = array();
        if ($data instanceof \Traversable) {
            $data = ArrayUtils::iteratorToArray($data);
        }
        if (is_object($data)) {
            $data = (array) $data;
        }
        if (! is_array($data)) {
            throw new \InvalidArgumentException(sprintf('Invalid data provided to %s; must be an array or Traversable', __METHOD__));
        }
        if (empty($data['instituteId'])) {
            if ($requiredFields['instituteId']) {
                $errors['instituteId'][] = 'instituteId is required';
                $problem = true;
                return $errors;
            }
        }
        $instituteId = $data['instituteId'];
        $feeStructure = new InstituteFeeStructure();
        $institute = $this->fetchAllInstitutes(array(
            'id' => $instituteId
        ), null, true)[0];
        if (! empty($institute)) {
            $feeStructure->setInstitute($institute);
        } else {
            $errors['instituteId'][] = sprintf('The instituteId: %s does not exist', $data['instituteId']);
            $problem = true;
        }
        if (empty($data['name'])) {
            if ($requiredFields['name']) {
                $errors['name'][] = 'name is required';
                $problem = true;
            }
        } else {
            $feeStructureByName = $this->fetchStructures(array(
                'name' => $data['name'],
                'instituteId' => $instituteId
            ))[0];
            if (! empty($feeStructureByName)) {
                $errors['name'][] = sprintf('The name: %s is already registered', $data['name']);
                $problem = true;
            } else {
                $feeStructure->setName($data['name']);
            }
        }
        if (empty($data['amount'])) {
            if ($requiredFields['amount']) {
                $errors['amount'][] = 'amount is required';
                $problem = true;
            }
        } else {
            $feeStructure->setAmount($data['amount']);
        }
        if ($problem) {
            return $errors;
        }
        if (isset($data['enabled'])) {
            $feeStructure->setEnabled($data['enabled']);
        }
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            $om->persist($feeStructure);
            $om->flush();
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $om->close();
            throw $e;
        }
       
        return $feeStructure->getId();
    }

    public function register($data) 
    {
        $problem = false;
        $requiredFields = array(
            'name' => true,
            'institudeAutoId'=>true,
            'emailId' => true,
            'phoneNumber' => true,
            'country' => true,
            'pincode' => true
        );
        $errors = array();
        $errors['name'] = array();
        $errors['institudeAutoId'] = array();
        $errors['emailId'] = array();
        $errors['phoneNumber'] = array();
        if ($data instanceof \Traversable) {
            $data = ArrayUtils::iteratorToArray($data);
        }
        if (is_object($data)) {
            $data = (array) $data;
        }
        if (! is_array($data)) {
            throw new \InvalidArgumentException(sprintf('Invalid data provided to %s; must be an array or Traversable', __METHOD__));
        }
        $institute = new Institute();
        if (empty($data['name'])) {
            if ($requiredFields['name']) {
                $errors['name'][] = 'name is required';
                $problem = true;
            }
        } else {
            $instituteG = $this->fetchAllInstitutes(array(
                'name' => $data['name']
            ))[0];
            if (! empty($instituteG)) {
                $errors['name'][] = sprintf('The name: %s is already registered', $data['name']);
                $problem = true;
            } else {
                $institute->setName($data['name']);
            }
        }
        
        if (empty($data['institudeAutoId'])) {
            if ($requiredFields['institudeAutoId']) {
                $errors['institudeAutoId'][] = 'Institute Id is required';
                $problem = true;
            }
        } else {
            $instituteAID = $this->fetchAllInstitutes(array(
                'institudeAutoId' => $data['institudeAutoId']
            ))[0];
           
            if (! empty($instituteAID)) {
                $errors['institudeAutoId'][] = sprintf('The Id: %s is already registered', $data['institudeAutoId']);
                $problem = true;
            } else {
                $institute->setinstitudeAutoId($data['institudeAutoId']);
            }
        }
        if (empty($data['emailId'])) {
            if ($requiredFields['emailId']) {
                $errors['emailId'][] = 'emailId is required';
                $problem = true;
            }
        } else {
            $instituteXT = $this->fetchAllInstitutes(array(
                'emailId' => $data['emailId']
            ))[0];
            if (! empty($instituteXT)) {
                $errors['emailId'][] = sprintf('emailId id: %s is already registered', $data['emailId']);
                $problem = true;
            } else {
                $institute->setEmailId($data['emailId']);
            }
        }
        // //////////
        if (empty($data['phoneNumber'])) {
            if ($requiredFields['phoneNumber']) {
                $errors['phoneNumber'][] = 'phoneNumber is required';
                $problem = true;
            }
        } else {
            $instituteXDT = $this->fetchAllInstitutes(array(
                'phoneNumber' => $data['phoneNumber']
            ))[0];
            if (! empty($instituteXDT)) {
                $errors['phoneNumber'][] = sprintf('phoneNumber : %s is already registered', $data['phoneNumber']);
                $problem = true;
            } else {
                $institute->setPhoneNumber($data['phoneNumber']);
            }
        }
        $om = $this->getOrmEntityMgr();
        
        //
        if (! empty($data['emailIdTwo'])) {
            $instituteXT12 = $this->fetchAllInstitutes(array(
                'emailIdTwo' => $data['emailIdTwo']
            ))[0];
            if (! empty($instituteXT12)) {
                $errors['emailIdTwo'][] = sprintf('emailId Two : %s is already registered', $data['emailIdTwo']);
                $problem = true;
            } else {
                $institute->setEmailIdTwo($data['emailIdTwo']);
            }
        }
        if (! empty($data['phoneNumberTwo'])) {
            $instituteXT13 = $this->fetchAllInstitutes(array(
                'phoneNumberTwo' => $data['phoneNumberTwo']
            ))[0];
            if (! empty($instituteXT13)) {
                $errors['phoneNumberTwo'][] = sprintf('phoneNumber Two : %s is already registered', $data['phoneNumberTwo']);
                $problem = true;
            } else {
                $institute->setPhoneNumberTwo($data['phoneNumberTwo']);
            }
        }
        if (! empty($data['phoneNumberThree'])) {
            $instituteXT13 = $this->fetchAllInstitutes(array(
                'phoneNumberThree' => $data['phoneNumberThree']
            ))[0];
            if (! empty($instituteXT13)) {
                $errors['phoneNumberThree'][] = sprintf('phoneNumber Three : %s is already registered', $data['phoneNumberThree']);
                $problem = true;
            } else {
                $institute->setPhoneNumberThree($data['phoneNumberThree']);
            }
        }
        if ($problem) {
            return $errors;
        }
        if (! empty($data['country'])) {
            $institute->setCountry($data['country']);
        }
        if (! empty($data['pincode'])) {
            $institute->setPincode($data['pincode']);
        }
        if (isset($data['enabled'])) {
            $institute->setEnabled($data['enabled']);
        }
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            $om->persist($institute);
            $om->flush($institute);
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $om->close();
            throw $e;
        }
        
        return $institute->getId();
    }
    public function deleteComponent($componentId)
    {
         
         
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
       $result= $connection->exec("DELETE FROM itfi_finmgmt_institute_fee_structure WHERE id='".$componentId."'");
       
       $connection->commit();
    return $result;
    }
}
