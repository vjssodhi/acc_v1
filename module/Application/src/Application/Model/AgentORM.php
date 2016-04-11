<?php
namespace Application\Model;

use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Traversable;
use Zend\Stdlib\ArrayUtils;
use Application\Utilities\NumberPlay;
use Application\Entity\Agent;
use Application\Entity\AgentPayment;
use Application\Entity\InstituteAgent;

class AgentORM extends CommonFetch
{

    /**
     *
     * @var EntityManager
     */
    protected $ormEntityMgr;

    public function updatePayment($agentEmail, $totalCommission, $paidAmmount)
    {
        $payment = new AgentPayment();
        $payment->setEmailId($agentEmail);
        $payment->setTotalCommission($totalCommission);
        $payment->setPaidAmmount($paidAmmount);
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            
            $om->persist($payment);
            $om->flush();
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $om->close();
            throw $e;
        }
        
        return $payment->getId();
    }

    public function register($data)
    {
        $data = NumberPlay::cleaner($data);
        $problem = false;
        $requiredFields = array(
            'name' => true,
            'emailId' => true,
            'mobile' => true,
            'address' => true,
            'enabled' => true,
            'commissionPercentage' => true
        );
        $errors = array();
        $errors['emailId'] = array();
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
        $agent = new Agent();
        //
        if (empty($data['name'])) {
            if ($requiredFields['name']) {
                $errors['name'][] = 'name is required';
                $problem = true;
            }
        } else {
            $agent->setName($data['name']);
        }
        if (empty($data['emailId'])) {
            if ($requiredFields['emailId']) {
                $errors['emailId'][] = 'email Id is required';
                $problem = true;
            }
        } else {
            $agentEml = $this->fetchAllAgents(array(
                'emailId' => $data['emailId']
            ))[0];
            if (! empty($agentEml)) {
                $errors['emailId'][] = sprintf('Email id: %s is already registered', $data['emailId']);
                $problem = true;
            } else {
                $agent->setEmailId($data['emailId']);
            }
        }
        if (empty($data['mobile'])) {
            if ($requiredFields['mobile']) {
                $errors['mobile'][] = 'mobile is required';
                $problem = true;
            }
        } else {
            $agentMbl = $this->fetchAllAgents(array(
                'mobile' => $data['mobile']
            ))[0];
            if (! empty($agentMbl)) {
                $errors['mobile'][] = sprintf('mobile : %s is already registered', $data['mobile']);
                $problem = true;
            } else {
                $agent->setMobile($data['mobile']);
            }
        }
        if ($problem) {
            return $errors;
        }
        //
        if (! empty($data['address'])) {
            $agent->setAddress($data['address']);
        }
        if (isset($data['commissionPercentage'])) {
            $agent->setCommissionPercentage($data['commissionPercentage']);
        }
        if (isset($data['enabled'])) {
            $agent->setEnabled($data['enabled']);
        }
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            
            $om->persist($agent);
            $om->flush();
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $om->close();
            throw $e;
        }
        
        return $agent->getId();
    }

    public function addToInstitute($instituteId, $agentId, $data)
    {
        $data = NumberPlay::cleaner($data);
        $problem = false;
        $requiredFields = array(
            'enabled' => true,
            'commissionPercentage' => true
        );
        $errors = array();
        $errors['emailId'] = array();
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
        $institute = $this->fetchAllInstitutes(array(
            'id' => $instituteId
        ), null, true)[0];
        
        if (empty($institute)) {
            return false;
        }
        $agent = $this->fetchAllAgents(array(
            'id' => $agentId
        ), null, true)[0];
        /* @var $agent Agent */
        if (empty($agent)) {
            return false;
        }
        
        $instituteAgent = $this->fetchInstituteAgents(array(
            'institute' => $institute,
            'agent' => $agent
        ), null, true)[0];
        if (empty($instituteAgent)) {
            $instituteAgent = new InstituteAgent();
        }
        
        //
        if ($problem) {
            return $errors;
        }
        //
        if (isset($data['commissionPercentage'])) {
            $instituteAgent->setCommissionPercentage($data['commissionPercentage']);
        }
        if (isset($data['enabled'])) {
            $instituteAgent->setEnabled($data['enabled']);
        }
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            $instituteAgent->setAgent($agent);
            $instituteAgent->setInstitute($institute);
            $om->persist($instituteAgent);
            $om->flush();
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $om->close();
            throw $e;
        }
        
        return $instituteAgent->getId();
    }

    public function update($id, $data)
    {
        $problem = false;
        $errors = array();
        $errors['emailId'] = array();
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
        $agent = $this->fetchAllAgents(array(
            'id' => $id
        ), null, true)[0];
        /* @var $agent Agent */
        if (empty($agent)) {
            return false;
        }
        $oldMobile = $agent->getMobile();
        $oldEmailId = $agent->getEmailId();
        if (! empty($data['emailId']) && ($data['emailId'] !== $oldEmailId)) {
            $agentEml = $this->fetchAllAgents(array(
                'emailId' => $data['emailId']
            ))[0];
            if (! empty($agentEml)) {
                $errors['emailId'][] = sprintf('Email id: %s is already registered', $data['emailId']);
                $problem = true;
            } else {
                $agent->setEmailId($data['emailId']);
            }
        }
        
        if (! empty($data['mobile']) && ($data['mobile'] !== $oldMobile)) {
            $agentMbl = $this->fetchAllAgents(array(
                'mobile' => $data['mobile']
            ))[0];
            if (! empty($agentMbl)) {
                $errors['mobile'][] = sprintf('mobile : %s is already registered', $data['mobile']);
                $problem = true;
            } else {
                $agent->setMobile($data['mobile']);
            }
        }
        if ($problem) {
            return $errors;
        }
        if (! empty($data['name'])) {
            $agent->setName($data['name']);
        }
        //
        if (! empty($data['address'])) {
            $agent->setAddress($data['address']);
        }
        if (isset($data['commissionPercentage'])) {
            $agent->setCommissionPercentage($data['commissionPercentage']);
        }
        if (isset($data['enabled'])) {
            $agent->setEnabled($data['enabled']);
        }
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            
            $om->persist($agent);
            $om->flush();
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $om->close();
            throw $e;
        }
        
        return $agent->getId();
    }
}