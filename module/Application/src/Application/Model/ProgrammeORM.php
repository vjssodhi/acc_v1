<?php
namespace Application\Model;

use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Traversable;
use Zend\Stdlib\ArrayUtils;
use Application\Entity\Institute;
use Application\Utilities\NumberPlay;
use Application\Entity\Programme;

class ProgrammeORM extends CommonFetch
{

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

    public function register($instituteId, $data)
    {
        $institute = $this->fetchAllInstitutes(array(
            'id' => $instituteId
        ), null, true)[0];
        if (empty($institute)) {
            return false;
        }
        $problem = false;
        $requiredFields = array(
            'name' => true,
            'enabled' => true,
            'feeAmount' => true,
            'feeCurrency' => true,
            'abbreviation' => false
        );
        $errors = array();
        $errors['name'] = array();
        $errors['abbreviation'] = array();
        if ($data instanceof \Traversable) {
            $data = ArrayUtils::iteratorToArray($data);
        }
        if (is_object($data)) {
            $data = (array) $data;
        }
        if (! is_array($data)) {
            throw new \InvalidArgumentException(sprintf('Invalid data provided to %s; must be an array or Traversable', __METHOD__));
        }
        $programme = new Programme();
        $programme->setInstitute($institute);
        if (empty($data['name'])) {
            if ($requiredFields['name']) {
                $errors['name'][] = 'name is required';
                $problem = true;
            }
        } else {
            $programmeG = $this->fetchAllProgrammes(array(
                'name' => $data['name'],
                'institute' => $institute
            ))[0];
            if (! empty($programmeG)) {
                $errors['name'][] = sprintf('The name: %s is already registered', $data['name']);
                $problem = true;
            } else {
                $programme->setName($data['name']);
            }
        }
        if (empty($data['abbreviation'])) {
            if ($requiredFields['abbreviation']) {
                $errors['abbreviation'][] = 'abbreviation is required';
                $problem = true;
            }
        } else {
            $programmeXT = $this->fetchAllProgrammes(array(
                'abbreviation' => $data['abbreviation'],
                'institute' => $institute
            ))[0];
            if (! empty($programmeXT)) {
                $errors['abbreviation'][] = sprintf('abbreviation: %s is already registered', $data['abbreviation']);
                $problem = true;
            } else {
                $programme->setAbbreviation($data['abbreviation']);
            }
        }
        //
        
        if ($problem) {
            return $errors;
        }
        if (! empty($data['feeAmount'])) {
            $programme->setFeeAmount($data['feeAmount']);
        }
        if (! empty($data['feeCurrency'])) {
            $programme->setFeeCurrency($data['feeCurrency']);
        }
        if (isset($data['enabled'])) {
            $programme->setEnabled($data['enabled']);
        }
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            $om->persist($programme);
            $om->flush($programme);
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $om->close();
            throw $e;
        }
        
        return $programme->getId();
    }

    public function update($id, $data)
    {
        $problem = false;
        $errors = array();
        $errors['name'] = array();
        $errors['abbreviation'] = array();
        if ($data instanceof \Traversable) {
            $data = ArrayUtils::iteratorToArray($data);
        }
        if (is_object($data)) {
            $data = (array) $data;
        }
        if (! is_array($data)) {
            throw new \InvalidArgumentException(sprintf('Invalid data provided to %s; must be an array or Traversable', __METHOD__));
        }
        $programme = $this->fetchAllProgrammes(array(
            'id' => $id
        ), null, true)[0];
        /* @var $programme Programme */
        $oldName = $programme->getName();
        $oldAbbr = $programme->getAbbreviation();
        $institute = $programme->getInstitute();
        if (! empty($data['name']) && ($oldName !== $data['name'])) {
            $newName = $data['name'];
            $programmeG = $this->fetchAllProgrammes(array(
                'name' => $newName,
                'institute' => $institute
            ))[0];
            if (! empty($programmeG)) {
                $errors['name'][] = sprintf('The name: %s is already registered', $data['name']);
                $problem = true;
            } else {
                $programme->setName($newName);
            }
        }
        
        if (! empty($data['abbreviation']) && ($oldAbbr !== $data['abbreviation'])) {
            $newAbbr = $data['abbreviation'];
            $programmeXT = $this->fetchAllProgrammes(array(
                'abbreviation' => $newAbbr,
                'institute' => $institute
            ))[0];
            if (! empty($programmeXT)) {
                $errors['abbreviation'][] = sprintf('abbreviation: %s is already registered', $data['abbreviation']);
                $problem = true;
            } else {
                $programme->setAbbreviation($newAbbr);
            }
        }
        //
        
        if ($problem) {
            return $errors;
        }
        if (! empty($data['feeAmount'])) {
            $programme->setFeeAmount($data['feeAmount']);
        }
        if (! empty($data['feeCurrency'])) {
            $programme->setFeeCurrency($data['feeCurrency']);
        }
        if (isset($data['enabled'])) {
            $programme->setEnabled($data['enabled']);
        }
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            $om->flush($programme);
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $om->close();
            throw $e;
        }
        
        return $programme->getId();
    }
}
