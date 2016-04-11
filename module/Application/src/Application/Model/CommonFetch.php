<?php
namespace Application\Model;

use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Traversable;
use Zend\Stdlib\ArrayUtils;
use Application\Entity\Institute;
use Application\Utilities\NumberPlay;
use Application\Entity\InstituteFeeStructure;
use Application\Entity\Agent;

class CommonFetch
{

    CONST INSTITUTE_ENTITY = 'Application\Entity\Institute';

    CONST FEE_STRUCT_ENTITY = 'Application\Entity\InstituteFeeStructure';

    CONST PROGRAMME_ENTITY = 'Application\Entity\Programme';

    CONST INSTITUTE_AGENT_ENTITY = 'Application\Entity\InstituteAgent';

    CONST AGENT_ENTITY = 'Application\Entity\Agent';

    CONST AGENT_PAYMENT_ENTITY = 'Application\Entity\AgentPayment';

    CONST STUDENT_ENTITY = 'Application\Entity\Student';

    CONST STUDENT_FEE_BBEAKDOWN = 'Application\Entity\StudentFeeBreakDown';

    /**
     *
     * @var EntityManager
     */
    protected $ormEntityMgr;

    public function getOrmEntityMgr()
    {
        return $this->ormEntityMgr;
    }

    public function setOrmEntityMgr($ormEntityMgr)
    {
        $this->ormEntityMgr = $ormEntityMgr;
    }

    public function fetchAllStudents($searchParams, $getQ = false, $returnObject = false, $returnedInfo = null)
    {
        if (! empty($searchParams)) {
            $searchParams = NumberPlay::cleaner($searchParams);
            if ($searchParams instanceof \Traversable) {
                $searchParams = ArrayUtils::iteratorToArray($searchParams);
            }
            if (is_object($searchParams)) {
                $searchParams = (array) $searchParams;
            }
            if (! is_array($searchParams)) {
                throw new \InvalidArgumentException(sprintf('Invalid searchParams provided to %s; must be an array or Traversable', __METHOD__));
            }
        }
        
        $params = array();
        $om = $this->getOrmEntityMgr();
        $qbs = $om->createQueryBuilder();
        $requiredFields = array();
        if (empty($returnObject)) {
            $requiredFields[] = 'IDENTITY(al.programme) as programmeId';
        }
        if (! empty($returnedInfo)) {
            foreach ($returnedInfo as $field) {
                $requiredFields[] = 'al.' . $field;
            }
            $qbs->select($requiredFields);
        } else {
            $requiredFields[] = 'al';
            $qbs->select($requiredFields);
        }
        $qbs->from(static::STUDENT_ENTITY, 'al');
        
        if (isset($searchParams['id'])) {
            $params[':idA'] = $searchParams['id'];
            $qbs->andWhere('al.id = :idA');
        }
        if (isset($searchParams['name'])) {
            $params[':nm'] = $searchParams['name'];
            $qbs->andWhere('al.name = :nm');
        }
        if (isset($searchParams['emailId'])) {
            $params[':email'] = $searchParams['emailId'];
            $qbs->andWhere('al.emailId = :email');
        }
        if (isset($searchParams['dateOfBirth'])) {
            $params[':dob'] = $searchParams['dateOfBirth'];
            $qbs->andWhere('al.dateOfBirth = :dob');
        }
        if (isset($searchParams['mobile'])) {
            $params[':mbl'] = $searchParams['mobile'];
            $qbs->andWhere('al.mobile = :mbl');
        }
        if (isset($searchParams['gender'])) {
            $params[':gen'] = $searchParams['gender'];
            $qbs->andWhere('al.gender = :gen');
        }
        
        if (isset($searchParams['agentId'])) {
            $agentId = $searchParams['agentId'];
            $agent = $this->fetchAllAgents(array(
                'id' => $agentId
            ), null, true)[0];
        }
        if (isset($searchParams['instituteId'])) {
            $instituteId = $searchParams['instituteId'];
            $institute = $this->fetchAllInstitutes(array(
                'id' => $instituteId
            ), null, true)[0];
        }
        if (isset($searchParams['agentId'])) {
            if (! empty($agent)) {
                $qbs->andWhere('al.agent=:agent');
                $params[':agent'] = $agent;
            }
        }
        if (isset($searchParams['feeAmount'])) {
            $params[':feeAmount'] = $searchParams['feeAmount'];
            $qbs->andWhere('al.feeAmount = :feeAmount');
        }
        
        if (isset($searchParams['feeCurrency'])) {
            $params[':feeCurrency'] = $searchParams['feeCurrency'];
            $qbs->andWhere('al.feeCurrency = :feeCurrency');
        }
        if (isset($searchParams['commissionToBePaidByInstitute'])) {
            $params[':cms'] = $searchParams['commissionToBePaidByInstitute'];
            $qbs->andWhere('al.commissionToBePaidByInstitute = :cms');
        }
        if (isset($searchParams['commissionStatus'])) {
            $params[':commissionStatus'] = $searchParams['commissionStatus'];
            $qbs->andWhere('al.commissionStatus = :commissionStatus');
        }
        if (isset($searchParams['enabled'])) {
            $params[':enabled'] = $searchParams['enabled'];
            $qbs->andWhere('al.enabled = :enabled');
        }
        
        $qbs->setParameters($params);
        $queryq = $qbs->getQuery();
        if ($getQ) {
            return $queryq;
        }
        if ($returnObject) {
            $results = $queryq->getResult();
            if (! empty($results) && ! empty($searchParams['instituteId'])) {
                foreach ($results as $key => $st) {
                    $nIstId = $st->getProgramme()
                        ->getInstitute()
                        ->getId();
                    if ($nIstId != $searchParams['instituteId']) {
                        unset($results[$key]);
                    }
                }
            }
        } else {
            $results = $queryq->getArrayResult();
            
            if (! empty($results)) {
                foreach ($results as $k => $result) {
                    $prgId = $result['programmeId'];
                    $prgInfo = $this->fetchAllProgrammes(array(
                        'id' => $prgId
                    ))[0];
                    $nIstId = $prgInfo['instituteId'];
                    $result[0]['programmeId'] = $prgId;
                    $result[0]['instituteId'] = $nIstId;
                    unset($result['programmeId']);
                    $result[0]['programmeInfo'] = $prgInfo;
                    $results[$k] = $result[0];
                    if (! empty($searchParams['instituteId'])) {
                        if ($nIstId != $searchParams['instituteId']) {
                            unset($results[$k]);
                        }
                    }
                }
            }
        }
        if (empty($results)) {
            return false;
        }
        return $results;
    }

    public function fetchStudentFeeBreakDown($searchParams, $getQ = false, $returnObject = false, $returnedInfo = null)
    {
        if (! empty($searchParams)) {
            $searchParams = NumberPlay::cleaner($searchParams);
            if ($searchParams instanceof \Traversable) {
                $searchParams = ArrayUtils::iteratorToArray($searchParams);
            }
            if (is_object($searchParams)) {
                $searchParams = (array) $searchParams;
            }
            if (! is_array($searchParams)) {
                throw new \InvalidArgumentException(sprintf('Invalid searchParams provided to %s; must be an array or Traversable', __METHOD__));
            }
        }
        
        $params = array();
        $om = $this->getOrmEntityMgr();
        $qbs = $om->createQueryBuilder();
        $requiredFields = array();
        if (! empty($returnedInfo)) {
            foreach ($returnedInfo as $field) {
                $requiredFields[] = 'al.' . $field;
            }
            $qbs->select($requiredFields);
        } else {
            $qbs->select(array(
                'al'
            ));
        }
        $qbs->from(static::STUDENT_FEE_BBEAKDOWN, 'al');
        if (isset($searchParams['id'])) {
            $params[':idA'] = $searchParams['id'];
            $qbs->andWhere('al.id = :idA');
        }
        if (isset($searchParams['studentId'])) {
            $params[':studentId'] = $searchParams['studentId'];
            $qbs->andWhere('al.studentId = :studentId');
        }
        if (isset($searchParams['componentId'])) {
            $params[':componentId'] = $searchParams['componentId'];
            $qbs->andWhere('al.componentId = :componentId');
        }
        if (isset($searchParams['amount'])) {
            $params[':amount'] = $searchParams['amount'];
            $qbs->andWhere('al.amount = :amount');
        }
        
        $qbs->setParameters($params);
        $queryq = $qbs->getQuery();
        if ($getQ) {
            return $queryq;
        }
        if ($returnObject) {
            $results = $queryq->getResult();
        } else {
            $results = $queryq->getArrayResult();
        }
        if (empty($results)) {
            return false;
        }
        return $results;
    }

    public function fetchAllProgrammes($searchParams, $getQ = false, $returnObject = false, $returnedInfo = null)
    {
        if (! empty($searchParams)) {
            $searchParams = NumberPlay::cleaner($searchParams);
            if ($searchParams instanceof \Traversable) {
                $searchParams = ArrayUtils::iteratorToArray($searchParams);
            }
            if (is_object($searchParams)) {
                $searchParams = (array) $searchParams;
            }
            if (! is_array($searchParams)) {
                throw new \InvalidArgumentException(sprintf('Invalid searchParams provided to %s; must be an array or Traversable', __METHOD__));
            }
        }
        
        $params = array();
        $om = $this->getOrmEntityMgr();
        $qbs = $om->createQueryBuilder();
        $requiredFields = array();
        if (empty($returnObject)) {
            $requiredFields[] = 'IDENTITY(al.institute) as instituteId';
        }
        if (! empty($returnedInfo)) {
            foreach ($returnedInfo as $field) {
                $requiredFields[] = 'al.' . $field;
            }
            $qbs->select($requiredFields);
        } else {
            $requiredFields[] = 'al';
            $qbs->select($requiredFields);
        }
        $qbs->from(static::PROGRAMME_ENTITY, 'al');
        
        if (isset($searchParams['institute'])) {
            $institute = $searchParams['institute'];
            if ($institute instanceof Institute) {
                $params[':inst'] = $institute;
                $qbs->andWhere('al.institute = :inst');
            }
        }
        if (isset($searchParams['instituteId'])) {
            $instituteId = $searchParams['instituteId'];
            $institute = $this->fetchAllInstitutes(array(
                'id' => $instituteId
            ), null, true)[0];
            if (! empty($institute)) {
                $params[':inst'] = $institute;
                $qbs->andWhere('al.institute = :inst');
            }
        }
        if (isset($searchParams['id'])) {
            $params[':idA'] = $searchParams['id'];
            $qbs->andWhere('al.id = :idA');
        }
        if (isset($searchParams['name'])) {
            $params[':nm'] = $searchParams['name'];
            $qbs->andWhere('al.name = :nm');
        }
        if (isset($searchParams['abbreviation'])) {
            $params[':abbr'] = $searchParams['abbreviation'];
            $qbs->andWhere('al.abbreviation = :abbr');
        }
        if (isset($searchParams['feeAmount'])) {
            $params[':feeAmt'] = $searchParams['feeAmount'];
            $qbs->andWhere('al.feeAmount = :feeAmt');
        }
        if (isset($searchParams['feeCurrency'])) {
            $params[':feeCurr'] = $searchParams['feeCurrency'];
            $qbs->andWhere('al.feeCurrency = :feeCurr');
        }
        if (isset($searchParams['enabled'])) {
            $params[':sta'] = $searchParams['enabled'];
            $qbs->andWhere('al.enabled = :sta');
        }
        
        $qbs->setParameters($params);
        $queryq = $qbs->getQuery();
        if ($getQ) {
            return $queryq;
        }
        if ($returnObject) {
            $results = $queryq->getResult();
        } else {
            $results = $queryq->getArrayResult();
            if (! empty($results)) {
                foreach ($results as $k => $result) {
                    $insId = $result['instituteId'];
                    $result[0]['instituteId'] = $insId;
                    unset($result['instituteId']);
                    $insInfo = $this->fetchAllInstitutes(array(
                        'id' => $insId
                    ))[0];
                    $result[0]['instituteInfo'] = $insInfo;
                    $results[$k] = $result[0];
                }
            }
        }
        if (empty($results)) {
            return false;
        }
        return $results;
    }

    public function fetchAgentPaymentInfo($searchParams, $getQ = false, $returnObject = false, $returnedInfo = null)
    {
        if (! empty($searchParams)) {
            $searchParams = NumberPlay::cleaner($searchParams);
            if ($searchParams instanceof \Traversable) {
                $searchParams = ArrayUtils::iteratorToArray($searchParams);
            }
            if (is_object($searchParams)) {
                $searchParams = (array) $searchParams;
            }
            if (! is_array($searchParams)) {
                throw new \InvalidArgumentException(sprintf('Invalid searchParams provided to %s; must be an array or Traversable', __METHOD__));
            }
        }
        
        $params = array();
        $om = $this->getOrmEntityMgr();
        $qbs = $om->createQueryBuilder();
        $requiredFields = array();
        if (! empty($returnedInfo)) {
            foreach ($returnedInfo as $field) {
                $requiredFields[] = 'al.' . $field;
            }
            $qbs->select($requiredFields);
        } else {
            $qbs->select(array(
                'al'
            ));
        }
        $qbs->from(static::AGENT_PAYMENT_ENTITY, 'al');
        if (isset($searchParams['id'])) {
            $params[':idA'] = $searchParams['id'];
            $qbs->andWhere('al.id = :idA');
        }
        if (isset($searchParams['emailId'])) {
            $params[':emailIdP'] = $searchParams['emailId'];
            $qbs->andWhere('al.emailId = :emailIdP');
        }
        $qbs->setParameters($params);
        $queryq = $qbs->getQuery();
        if ($getQ) {
            return $queryq;
        }
        if ($returnObject) {
            $results = $queryq->getResult();
        } else {
            $results = $queryq->getArrayResult();
        }
        if (empty($results)) {
            return false;
        }
        return $results;
    }

    public function fetchAllInstitutes($searchParams, $getQ = false, $returnObject = false, $returnedInfo = null)
    {
        if (! empty($searchParams)) {
            $searchParams = NumberPlay::cleaner($searchParams);
            if ($searchParams instanceof \Traversable) {
                $searchParams = ArrayUtils::iteratorToArray($searchParams);
            }
            if (is_object($searchParams)) {
                $searchParams = (array) $searchParams;
            }
            if (! is_array($searchParams)) {
                throw new \InvalidArgumentException(sprintf('Invalid searchParams provided to %s; must be an array or Traversable', __METHOD__));
            }
        }
        
        $params = array();
        $om = $this->getOrmEntityMgr();
        $qbs = $om->createQueryBuilder();
        $requiredFields = array();
        if (! empty($returnedInfo)) {
            foreach ($returnedInfo as $field) {
                $requiredFields[] = 'al.' . $field;
            }
            $qbs->select($requiredFields);
        } else {
            $qbs->select(array(
                'al'
            ));
        }
        $qbs->from(static::INSTITUTE_ENTITY, 'al');
        if (isset($searchParams['id'])) {
            $params[':idA'] = $searchParams['id'];
            $qbs->andWhere('al.id = :idA');
        }
        if (isset($searchParams['name'])) {
            $params[':nm'] = $searchParams['name'];
            $qbs->andWhere('al.name = :nm');
        }
        if (isset($searchParams['institudeAutoId'])) {
            $params[':aid'] = $searchParams['institudeAutoId'];
            $qbs->andWhere('al.institudeAutoId = :aid');
        }
        if (isset($searchParams['emailId'])) {
            $params[':emailIdP'] = $searchParams['emailId'];
            $qbs->andWhere('al.emailId = :emailIdP');
        }
        if (isset($searchParams['emailIdTwo'])) {
            $params[':emailIdTwoE'] = $searchParams['emailIdTwo'];
            $qbs->andWhere('al.emailIdTwo = :emailIdTwoE');
        }
        if (isset($searchParams['phoneNumber'])) {
            $params[':phn'] = $searchParams['phoneNumber'];
            $qbs->andWhere('al.phoneNumber = :phn');
        }
        if (isset($searchParams['phoneNumberTwo'])) {
            $params[':ph2'] = $searchParams['phoneNumberTwo'];
            $qbs->andWhere('al.phoneNumberTwo = :ph2');
        }
        if (isset($searchParams['phoneNumberThree'])) {
            $params[':ph3'] = $searchParams['phoneNumberThree'];
            $qbs->andWhere('al.phoneNumberThree = :ph3');
        }
        if (isset($searchParams['country'])) {
            $params[':ctry'] = $searchParams['country'];
            $qbs->andWhere('al.country = :ctry');
        }
        
        if (isset($searchParams['pincode'])) {
            $params[':pCode'] = $searchParams['pincode'];
            $qbs->andWhere('al.pincode = :pCode');
        }
        if (isset($searchParams['enabled'])) {
            $params[':sta'] = $searchParams['enabled'];
            $qbs->andWhere('al.enabled = :sta');
        }
        $qbs->setParameters($params);
        $queryq = $qbs->getQuery();
        if ($getQ) {
            return $queryq;
        }
        if ($returnObject) {
            $results = $queryq->getResult();
        } else {
            $results = $queryq->getArrayResult();
        }
        if (empty($results)) {
            return false;
        }
        return $results;
    }

    public function fetchStructures($searchParams, $getQ = false, $returnObject = false, $returnedInfo = null)
    {
        if (! empty($searchParams)) {
            $searchParams = NumberPlay::cleaner($searchParams);
            if ($searchParams instanceof \Traversable) {
                $searchParams = ArrayUtils::iteratorToArray($searchParams);
            }
            if (is_object($searchParams)) {
                $searchParams = (array) $searchParams;
            }
            if (! is_array($searchParams)) {
                throw new \InvalidArgumentException(sprintf('Invalid searchParams provided to %s; must be an array or Traversable', __METHOD__));
            }
        }
        
        $params = array();
        $om = $this->getOrmEntityMgr();
        $qbs = $om->createQueryBuilder();
        if (empty($returnObject)) {
            $requiredFields[] = 'IDENTITY(al.institute) as instituteId';
        }
        if (! empty($returnedInfo)) {
            foreach ($returnedInfo as $field) {
                $requiredFields[] = 'al.' . $field;
            }
            $qbs->select($requiredFields);
        } else {
            $requiredFields[] = 'al';
            $qbs->select($requiredFields);
        }
        
        $qbs->from(static::FEE_STRUCT_ENTITY, 'al');
        if (isset($searchParams['id'])) {
            $params[':idA'] = $searchParams['id'];
            $qbs->andWhere('al.id = :idA');
        }
        if (isset($searchParams['name'])) {
            $params[':nm'] = $searchParams['name'];
            $qbs->andWhere('al.name = :nm');
        }
        if (isset($searchParams['institute'])) {
            $institute = $searchParams['institute'];
            if ($institute instanceof Institute) {
                $params[':inst'] = $institute;
                $qbs->andWhere('al.institute = :inst');
            }
        }
        
        if (isset($searchParams['instituteId'])) {
            $instituteId = $searchParams['instituteId'];
            $institute = $this->fetchAllInstitutes(array(
                'id' => $instituteId
            ), null, true)[0];
            if (! empty($institute)) {
                $params[':inst'] = $institute;
                $qbs->andWhere('al.institute = :inst');
            }
        }
        if (isset($searchParams['amount'])) {
            $params[':amount'] = $searchParams['amount'];
            $qbs->andWhere('al.amount = :amount');
        }
        if (isset($searchParams['enabled'])) {
            $params[':sta'] = $searchParams['enabled'];
            $qbs->andWhere('al.enabled = :sta');
        }
        
        $qbs->setParameters($params);
        $queryq = $qbs->getQuery();
        
        if ($getQ) {
            return $queryq;
        }
        if ($returnObject) {
            $results = $queryq->getResult();
        } else {
            $results = $queryq->getArrayResult();
            if (! empty($results)) {
                foreach ($results as $k => $result) {
                    $result[0]['instituteId'] = $result['instituteId'];
                    unset($result['instituteId']);
                    $results[$k] = $result[0];
                }
            }
        }
        if (empty($results)) {
            return false;
        }
        return $results;
    }

    public function fetchInstituteAgents($searchParams, $getQ = false, $returnObject = false, $returnedInfo = null)
    {
        if (! empty($searchParams)) {
            $searchParams = NumberPlay::cleaner($searchParams);
            if ($searchParams instanceof \Traversable) {
                $searchParams = ArrayUtils::iteratorToArray($searchParams);
            }
            if (is_object($searchParams)) {
                $searchParams = (array) $searchParams;
            }
            if (! is_array($searchParams)) {
                throw new \InvalidArgumentException(sprintf('Invalid searchParams provided to %s; must be an array or Traversable', __METHOD__));
            }
        }
        
        $params = array();
        $om = $this->getOrmEntityMgr();
        $qbs = $om->createQueryBuilder();
        if (empty($returnObject)) {
            $requiredFields[] = 'IDENTITY(al.institute) as instituteId';
            $requiredFields[] = 'IDENTITY(al.agent) as agentId';
        }
        if (! empty($returnedInfo)) {
            foreach ($returnedInfo as $field) {
                $requiredFields[] = 'al.' . $field;
            }
            $qbs->select($requiredFields);
        } else {
            $requiredFields[] = 'al';
            $qbs->select($requiredFields);
        }
        
        $qbs->from(static::INSTITUTE_AGENT_ENTITY, 'al');
        if (isset($searchParams['id'])) {
            $params[':idA'] = $searchParams['id'];
            $qbs->andWhere('al.id = :idA');
        }
        if (isset($searchParams['institute'])) {
            $institute = $searchParams['institute'];
            if ($institute instanceof Institute) {
                $params[':inst'] = $institute;
                $qbs->andWhere('al.institute = :inst');
            }
        }
        
        if (isset($searchParams['instituteId'])) {
            $instituteId = $searchParams['instituteId'];
            $institute = $this->fetchAllInstitutes(array(
                'id' => $instituteId
            ), null, true)[0];
            if (! empty($institute)) {
                $params[':inst'] = $institute;
                $qbs->andWhere('al.institute = :inst');
            }
        }
        if (isset($searchParams['agent'])) {
            $agent = $searchParams['agent'];
            if ($agent instanceof Agent) {
                $params[':agn1'] = $agent;
                $qbs->andWhere('al.agent = :agn1');
            }
        }
        if (isset($searchParams['agentId'])) {
            $agentId = $searchParams['agentId'];
            $agent = $this->fetchAllAgents(array(
                'id' => $agentId
            ), null, true)[0];
            if (! empty($agent)) {
                $params[':instAgnt'] = $agent;
                $qbs->andWhere('al.agent = :instAgnt');
            }
        }
        if (isset($searchParams['enabled'])) {
            $params[':sta'] = $searchParams['enabled'];
            $qbs->andWhere('al.enabled = :sta');
        }
        
        $qbs->setParameters($params);
        $queryq = $qbs->getQuery();
        
        if ($getQ) {
            return $queryq;
        }
        if ($returnObject) {
            $results = $queryq->getResult();
        } else {
            $results = $queryq->getArrayResult();
            if (! empty($results)) {
                foreach ($results as $k => $result) {
                    $result[0]['instituteId'] = $result['instituteId'];
                    $result[0]['agentId'] = $result['agentId'];
                    $inf = $this->fetchAllAgents(array(
                        'id' => $result['agentId']
                    ))[0];
                    $result[0] = array_merge($result[0], $inf);
                    unset($result['instituteId']);
                    unset($result['agentId']);
                    $results[$k] = $result[0];
                }
            }
        }
        if (empty($results)) {
            return false;
        }
        return $results;
    }


    public function fetchAllAgents($searchParams, $getQ = false, $returnObject = false, $returnedInfo = null)
    {
        if (! empty($searchParams)) {
            $searchParams = NumberPlay::cleaner($searchParams);
            if ($searchParams instanceof \Traversable) {
                $searchParams = ArrayUtils::iteratorToArray($searchParams);
            }
            if (is_object($searchParams)) {
                $searchParams = (array) $searchParams;
            }
            if (! is_array($searchParams)) {
                throw new \InvalidArgumentException(sprintf('Invalid searchParams provided to %s; must be an array or Traversable', __METHOD__));
            }
        }
        
        $params = array();
        $om = $this->getOrmEntityMgr();
        $qbs = $om->createQueryBuilder();
        $requiredFields = array();
        if (! empty($returnedInfo)) {
            foreach ($returnedInfo as $field) {
                $requiredFields[] = 'al.' . $field;
            }
            $qbs->select($requiredFields);
        } else {
            $requiredFields[] = 'al';
            $qbs->select($requiredFields);
        }
        $qbs->from(static::AGENT_ENTITY, 'al');
        $qbs->where('al.createdOn > 1000');
        if (isset($searchParams['id'])) {
            $params[':idA'] = $searchParams['id'];
            $qbs->andWhere('al.id = :idA');
        }
        if (isset($searchParams['name'])) {
            $params[':nm'] = $searchParams['name'];
            $qbs->andWhere('al.name = :nm');
        }
        if (isset($searchParams['address'])) {
            $params[':add'] = $searchParams['address'];
            $qbs->andWhere('al.address = :add');
        }
        if (isset($searchParams['mobile'])) {
            $params[':mbl'] = $searchParams['mobile'];
            $qbs->andWhere('al.mobile = :mbl');
        }
        if (isset($searchParams['emailId'])) {
            $params[':eml'] = $searchParams['emailId'];
            $qbs->andWhere('al.emailId = :eml');
        }
        if (isset($searchParams['enabled'])) {
            $params[':sta'] = $searchParams['enabled'];
            $qbs->andWhere('al.enabled = :sta');
        }
        if (isset($data['commissionPercentage'])) {
            $params[':cmm'] = $searchParams['commissionPercentage'];
            $qbs->andWhere('al.commissionPercentage = :cmm');
        }
        $qbs->setParameters($params);
        
        $queryq = $qbs->getQuery();
        if ($getQ) {
            return $queryq;
        }
        if ($returnObject) {
            $results = $queryq->getResult();
        } else {
            $results = $queryq->getArrayResult();
        }
        if (empty($results)) {
            return false;
        }
        
        return $results;
    }
}
