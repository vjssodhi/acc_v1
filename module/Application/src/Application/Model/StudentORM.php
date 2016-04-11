<?php
namespace Application\Model;

use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Traversable;
use Zend\Stdlib\ArrayUtils;
use Application\Utilities\NumberPlay;
use Application\Entity\Programme;
use Application\Entity\Agent;
use Application\Entity\Student;
use Application\Entity\StudentFeeBreakDown;

class StudentORM extends CommonFetch
{

    public function updateUser($id, $data, $structOptions)
    {
        $data = NumberPlay::cleaner($data);
        
        $problem = false;
        $errors = array();
        $errors['emailId'] = array();
        $errors['mobile'] = array();
        $errors['programmeId'] = array();
        $errors['agentId'] = array();
        if ($data instanceof \Traversable) {
            $data = ArrayUtils::iteratorToArray($data);
        }
        if (is_object($data)) {
            $data = (array) $data;
        }
        if (! is_array($data)) {
            throw new \InvalidArgumentException(sprintf('Invalid data provided to %s; must be an array or Traversable', __METHOD__));
        }
        $student = $this->fetchAllStudents(array(
            'id' => $id
        ), null, true)[0];
        if (empty($student)) {
            return false;
        }
        /* @var $student Student */
        $oldEmaildId = $student->getEmailId();
        $oldMobile = $student->getMobile();
        if (! empty($data['emailId']) && ($data['emailId'] !== $oldEmaildId)) {
            $stByEmail = $this->fetchAllStudents(array(
                'emailId' => $data['emailId']
            ))[0];
            if (! empty($stByEmail)) {
                $errors['emailId'][] = sprintf('Email id: %s is already registered', $data['emailId']);
                $problem = true;
            } else {
                $student->setEmailId($data['emailId']);
            }
        }
        
        if (! empty($data['mobile']) && ($data['mobile'] !== $oldMobile)) {
            $stByMobile = $this->fetchAllStudents(array(
                'mobile' => $data['mobile']
            ))[0];
            if (! empty($stByMobile)) {
                $errors['mobile'][] = sprintf('mobile : %s is already registered', $data['mobile']);
                $problem = true;
            } else {
                $student->setMobile($data['mobile']);
            }
        }
        if (! empty($data['agentId'])) {
            $agentId = $data['agentId'];
            $agent = $this->fetchAllAgents(array(
                'id' => $agentId
            ), null, true)[0];
            if (empty($agent)) {
                $errors['agentId'][] = sprintf('agentId : %s is invalid', $data['agentId']);
                $problem = true;
            } else {
                $student->setAgent($agent);
            }
        }
        if (! empty($data['programmeId'])) {
            $programmeId = $data['programmeId'];
            $programme = $this->fetchAllProgrammes(array(
                'id' => $programmeId
            ), null, true)[0];
            if (empty($programme)) {
                $errors['programmeId'][] = sprintf('programmeId : %s is invalid', $data['programmeId']);
                $problem = true;
            } else {
                $student->setProgramme($programme);
            }
        }
        if ($problem) {
            return $errors;
        }
        if (! empty($data['name'])) {
            $student->setName($data['name']);
        }
        if (! empty($data['dateOfBirth'])) {
            $student->setDateOfBirth($data['dateOfBirth']);
        }
        if (! empty($data['gender'])) {
            $student->setGender($data['gender']);
        }
        if (! empty($data['address'])) {
            $student->setAddress($data['address']);
        }
        if (isset($data['commissionStatus'])) {
            $student->setCommissionStatus($data['commissionStatus']);
        }
        if (! empty($data['commissionToBePaidByInstitute'])) {
            $student->setCommissionToBePaidByInstitute($data['commissionToBePaidByInstitute']);
        }
        if (isset($data['feeAmount'])) {
            $student->setFeeAmount($data['feeAmount']);
        }
        if (isset($data['feeCurrency'])) {
            $student->setFeeCurrency($data['feeCurrency']);
        }
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            $om->flush($student);
            $connection->commit();
        } catch (\Exception $e) {
            
            $connection->rollBack();
            $om->close();
            throw $e;
        }
        $studentId = $student->getId();
        foreach ($structOptions as $kk => $structOption) {
            $exp = explode('---', $kk);
            $componentId = $exp[1];
            $componentName = $exp[0];
            $this->saveFeeBreakDown($studentId, $componentId, $data[$kk]);
        }
        return $student->getId();
    }

    public function getstudentfee($studentId)
    {
        
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
       $result= $connection->fetchAll("select feeAmount, feeCurrency from itfi_finmgmt_student where id='".$studentId."'");
       
       $connection->commit();
      
    return $result;
   
    }
    
    public function register($data, $structOptions)
    {
        $data = NumberPlay::cleaner($data);
        $problem = false;
        $errors = array();
        $requiredFields = array(
            'emailId' => true,
            'agentId' => true,
            'programmeId' => true,
            'mobile' => true
        );
        $errors['emailId'] = array();
        $errors['agentId'] = array();
        $errors['programmeId'] = array();
        $errors['mobile'] = array();
        if ($data instanceof \Traversable) {
            $data = ArrayUtils::iteratorToArray($data);
        }
        if (is_object($data)) {
            $data = (array) $data;
        }
        if (! is_array($data)) {
            throw new \InvalidArgumentException(sprintf('Invalid data provided to %s; must be an array or Traversable', __METHOD__));
        }
        $student = new Student();
        if (empty($data['emailId'])) {
            if ($requiredFields['emailId']) {
                $errors['emailId'][] = 'emailId is required';
                $problem = true;
            }
        } else {
            $stuByEMail = $this->fetchAllStudents(array(
                'emailId' => $data['emailId']
            ))[0];
            if (! empty($stuByEMail)) {
                $errors['emailId'][] = sprintf('Email id: %s is already registered', $data['emailId']);
                $problem = true;
            } else {
                $student->setEmailId($data['emailId']);
            }
        }
        if (empty($data['mobile'])) {
            if ($requiredFields['mobile']) {
                $errors['mobile'][] = 'mobile is required';
                $problem = true;
            }
        } else {
            $stuByMbl = $this->fetchAllStudents(array(
                'mobile' => $data['mobile']
            ))[0];
            if (! empty($stuByMbl)) {
                $errors['mobile'][] = sprintf('mobile : %s is already registered', $data['mobile']);
                $problem = true;
            } else {
                $student->setMobile($data['mobile']);
            }
        }
        if (empty($data['agentId'])) {
            if ($requiredFields['agentId']) {
                $errors['agentId'][] = 'agentId is required';
                $problem = true;
            }
        } else {
            $agentId = $data['agentId'];
            $agent = $this->fetchAllAgents(array(
                'id' => $agentId
            ), null, true)[0];
            if (empty($agent)) {
                $errors['agentId'][] = sprintf('agentId : %s is invalid', $data['agentId']);
                $problem = true;
            } else {
                $student->setAgent($agent);
            }
        }
        if (empty($data['programmeId'])) {
            if ($requiredFields['programmeId']) {
                $errors['programmeId'][] = 'programmeId is required';
                $problem = true;
            }
        } else {
            $programmeId = $data['programmeId'];
            $programme = $this->fetchAllProgrammes(array(
                'id' => $programmeId
            ), null, true)[0];
            if (empty($programme)) {
                $errors['programmeId'][] = sprintf('programmeId : %s is invalid', $data['programmeId']);
                $problem = true;
            } else {
                $student->setProgramme($programme);
            }
        }
        
        $om = $this->getOrmEntityMgr();
        
        if ($problem) {
            return $errors;
        }
        if (! empty($data['name'])) {
            $student->setName($data['name']);
        }
        if (! empty($data['dateOfBirth'])) {
            $student->setDateOfBirth($data['dateOfBirth']);
        }
        if (! empty($data['gender'])) {
            $student->setGender($data['gender']);
        }
        if (! empty($data['address'])) {
            $student->setAddress($data['address']);
        }
        if (! empty($data['city'])) {
            $student->setCity($data['city']);
        }
        if (! empty($data['country'])) {
            $student->setCountry($data['country']);
        }
        if (! empty($data['zipcode'])) {
            $student->setZipcode($data['zipcode']);
        }
        if (isset($data['commissionStatus'])) {
            $student->setCommissionStatus($data['commissionStatus']);
        }
        if (! empty($data['commissionToBePaidByInstitute'])) {
            $student->setCommissionToBePaidByInstitute($data['commissionToBePaidByInstitute']);
        }
        if (isset($data['feeAmount'])) {
            $student->setFeeAmount($data['feeAmount']);
        }
        
        if (isset($data['feeCurrency'])) {
            $student->setFeeCurrency($data['feeCurrency']);
        }
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            
            $om->persist($student);
            $om->flush();
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $om->close();
            throw $e;
        }
        $studentId = $student->getId();
        foreach ($structOptions as $kk => $structOption) {
            $exp = explode('---', $kk);
            $componentId = $exp[1];
            $componentName = $exp[0];
            $this->saveFeeBreakDown($studentId, $componentId, $data[$kk]);
        }
        return $student->getId();
    }

   
    public function saveFeeBreakDown($studentId, $componentId, $amtInvoiced)
    {
        $breakDownObj = $this->fetchStudentFeeBreakDown(array(
            'studentId' => $studentId,
            'componentId' => $componentId,
            'amountPaid' => $amtInvoiced
        ), null, true)[0];
        if (empty($breakDownObj)) {
            $breakDownObj = new StudentFeeBreakDown();
        }
        $breakDownObj->setAmount($amtInvoiced);
        $breakDownObj->setStudentId($studentId);
        $breakDownObj->setComponentId($componentId);
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            $om->persist($breakDownObj);
            $om->flush($breakDownObj);
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $om->close();
            throw $e;
        }
    }
    
    
}
