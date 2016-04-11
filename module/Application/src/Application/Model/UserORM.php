<?php
namespace Application\Model;

use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Traversable;
use Zend\Stdlib\ArrayUtils;
use Application\Entity\User;
use Application\Utilities\NumberPlay;

class UserORM
{

    CONST USER_ENTITY = 'Application\Entity\User';

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

    public function hashUserPassword($password)
    {
        $password_options = array(
            'cost' => 12
        );
        return password_hash($password, PASSWORD_DEFAULT, $password_options);
    }

    public function updateUser($id, $data)
    {
        $data = NumberPlay::cleaner($data, 
            array(
                'password',
                'passwordConfirm'
            ));
        
        $problem = false;
        $errors = array();
        $errors['loginId'] = array();
        $errors['password'] = array();
        $errors['personalEmailId'] = array();
        if ($data instanceof \Traversable) {
            $data = ArrayUtils::iteratorToArray($data);
        }
        if (is_object($data)) {
            $data = (array) $data;
        }
        if (! is_array($data)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid data provided to %s; must be an array or Traversable', __METHOD__));
        }
        $user = $this->fetchAll(array(
            'userId' => $id
        ), null, true)[0];
        if (empty($user)) {
            return false;
        }
        /* @var $user User */
        $oldLoginId = $user->getLoginId();
        $oldMobile = $user->getMobile();
        $oldPersonalEmailId = $user->getPersonalEmailId();
        if (! empty($data['loginId']) && ($data['loginId'] !== $oldLoginId)) {
            $userG = $this->fetchAll(
                array(
                    'loginId' => $data['loginId']
                ))[0];
            if (! empty($userG)) {
                $errors['loginId'][] = sprintf('Login id: %s is already registered', 
                    $data['loginId']);
                $problem = true;
            } else {
                $user->setLoginId($data['loginId']);
            }
        }
        if (! empty($data['password'])) {
            $passwordHash = $this->hashUserPassword($data['password']);
            $user->setPassword($passwordHash);
        }
        if (! empty($data['personalEmailId']) && ($data['personalEmailId'] !== $oldPersonalEmailId)) {
            $userXT = $this->fetchAll(
                array(
                    'personalEmailId' => $data['personalEmailId']
                ))[0];
            if (! empty($userXT)) {
                $errors['personalEmailId'][] = sprintf('Email id: %s is already registered', 
                    $data['personalEmailId']);
                $problem = true;
            } else {
                $user->setPersonalEmailId($data['personalEmailId']);
            }
        }
        
        if (! empty($data['mobile']) && ($data['mobile'] !== $oldMobile)) {
            $userXDT = $this->fetchAll(
                array(
                    'mobile' => $data['mobile']
                ))[0];
            if (! empty($userXDT)) {
                $errors['mobile'][] = sprintf('mobile : %s is already registered', $data['mobile']);
                $problem = true;
            } else {
                $user->setMobile($data['mobile']);
            }
        }
        $om = $this->getOrmEntityMgr();
        if ($problem) {
            return $errors;
        }
        
        if (! empty($data['fullName'])) {
            $user->setFullName($data['fullName']);
        }
        if (! empty($data['dateOfBirth'])) {
            $user->setDateOfBirth($data['dateOfBirth']);
        }
        if (! empty($data['gender'])) {
            $user->setGender($data['gender']);
        }
        if (! empty($data['emailVerificationStatus'])) {
            $user->setEmailVerified($data['emailVerificationStatus']);
        }
        if (! empty($data['marritalStatus'])) {
            $user->setMarritalStatus($data['marritalStatus']);
        }
        if (! empty($data['nationality'])) {
            $user->setNationality($data['nationality']);
        }
        if (! empty($data['state'])) {
            $user->setState($data['state']);
        }
        if (! empty($data['city'])) {
            $user->setCity($data['city']);
        }
        if (! empty($data['district'])) {
            $user->setDistrict($data['district']);
        }
        if (! empty($data['pincode'])) {
            $user->setPincode($data['pincode']);
        }
        if (! empty($data['accessLevel'])) {
            $user->setAccessLevel($data['accessLevel']);
        }
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            $om->flush($user);
            $connection->commit();
        } catch (\Exception $e) {
            
            $connection->rollBack();
            $om->close();
            throw $e;
        }
        
        return $user->getId();
    }

    public function registeruser($data)
    {
        $data = NumberPlay::cleaner($data, 
            array(
                'password',
                'passwordConfirm'
            ));
        $problem = false;
        $requiredFields = array(
            'loginId' => true,
            'password' => true,
            'personalEmailId' => true,
            'accessLevel' => true
        );
        $errors = array();
        $errors['loginId'] = array();
        $errors['password'] = array();
        $errors['personalEmailId'] = array();
        if ($data instanceof \Traversable) {
            $data = ArrayUtils::iteratorToArray($data);
        }
        if (is_object($data)) {
            $data = (array) $data;
        }
        if (! is_array($data)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid data provided to %s; must be an array or Traversable', __METHOD__));
        }
        $user = new User();
        $user->setBasicBioComplete(false);
        if (empty($data['loginId'])) {
            if ($requiredFields['loginId']) {
                $errors['loginId'][] = 'loginId is required';
                $problem = true;
            }
        } else {
            $userG = $this->fetchAll(
                array(
                    'loginId' => $data['loginId']
                ))[0];
            if (! empty($userG)) {
                $errors['loginId'][] = sprintf('Login id: %s is already registered', 
                    $data['loginId']);
                $problem = true;
            } else {
                $user->setLoginId($data['loginId']);
            }
        }
        if (empty($data['password'])) {
            if ($requiredFields['password']) {
                $errors['password'][] = 'Password is required';
                $problem = true;
            }
        } else {
            $passwordHash = $this->hashUserPassword($data['password']);
            $user->setPassword($passwordHash);
        }
        if (empty($data['personalEmailId'])) {
            if ($requiredFields['personalEmailId']) {
                $errors['personalEmailId'][] = 'personalEmailId is required';
                $problem = true;
            }
        } else {
            $userXT = $this->fetchAll(
                array(
                    'personalEmailId' => $data['personalEmailId']
                ))[0];
            if (! empty($userXT)) {
                $errors['personalEmailId'][] = sprintf('Email id: %s is already registered', 
                    $data['personalEmailId']);
                $problem = true;
            } else {
                $user->setPersonalEmailId($data['personalEmailId']);
            }
        }
        // //////////
        if (empty($data['mobile'])) {
            if ($requiredFields['mobile']) {
                $errors['mobile'][] = 'mobile is required';
                $problem = true;
            }
        } else {
            $userXDT = $this->fetchAll(
                array(
                    'mobile' => $data['mobile']
                ))[0];
            if (! empty($userXDT)) {
                $errors['mobile'][] = sprintf('mobile : %s is already registered', $data['mobile']);
                $problem = true;
            } else {
                $user->setMobile($data['mobile']);
            }
        }
        $om = $this->getOrmEntityMgr();
        
        if ($problem) {
            return $errors;
        }
        //
        if (! empty($data['fullName'])) {
            $user->setFullName($data['fullName']);
        }
        if (! empty($data['dateOfBirth'])) {
            $user->setDateOfBirth($data['dateOfBirth']);
        }
        if (! empty($data['gender'])) {
            $user->setGender($data['gender']);
        }
        if (! empty($data['emailVerificationStatus'])) {
            $user->setEmailVerified($data['emailVerificationStatus']);
        }
        if (! empty($data['marritalStatus'])) {
            $user->setMarritalStatus($data['marritalStatus']);
        }
        if (! empty($data['nationality'])) {
            $user->setNationality($data['nationality']);
        }
        if (! empty($data['state'])) {
            $user->setState($data['state']);
        }
        if (! empty($data['city'])) {
            $user->setCity($data['city']);
        }
        if (! empty($data['district'])) {
            $user->setDistrict($data['district']);
        }
        if (! empty($data['pincode'])) {
            $user->setPincode($data['pincode']);
        }
        if (! empty($data['accessLevel'])) {
            $user->setAccessLevel($data['accessLevel']);
        }
        
        $om = $this->getOrmEntityMgr();
        $connection = $om->getConnection();
        $connection->beginTransaction();
        try {
            
            $om->persist($user);
            $om->flush($user);
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $om->close();
            throw $e;
        }
        
        return $user->getId();
    }

    public function fetchAll($searchParams, $getQ = false, $returnObject = false, $returnedInfo = null)
    {
        if (! empty($searchParams)) {
            $searchParams = NumberPlay::cleaner($searchParams, 
                array(
                    'password',
                    'passwordConfirm'
                ));
            if ($searchParams instanceof \Traversable) {
                $searchParams = ArrayUtils::iteratorToArray($searchParams);
            }
            if (is_object($searchParams)) {
                $searchParams = (array) $searchParams;
            }
            if (! is_array($searchParams)) {
                throw new \InvalidArgumentException(
                    sprintf('Invalid searchParams provided to %s; must be an array or Traversable', 
                        __METHOD__));
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
        $qbs->from(static::USER_ENTITY, 'al');
        if (isset($searchParams['userId'])) {
            $params[':idA'] = $searchParams['userId'];
            $qbs->andWhere('al.id = :idA');
        }
        if (isset($searchParams['fullName'])) {
            $params[':nm'] = $searchParams['fullName'];
            $qbs->andWhere('al.fullName = :nm');
        }
        if (isset($searchParams['loginId'])) {
            $params[':loginP'] = $searchParams['loginId'];
            $qbs->andWhere('al.loginId = :loginP');
        }
        if (isset($searchParams['dateOfBirth'])) {
            $params[':dob'] = $searchParams['dateOfBirth'];
            $qbs->andWhere('al.dateOfBirth = :dob');
        }
        if (isset($searchParams['gender'])) {
            $params[':gen'] = $searchParams['gender'];
            $qbs->andWhere('al.gender = :gen');
        }
        if (isset($searchParams['mobile'])) {
            $params[':mbl'] = $searchParams['mobile'];
            $qbs->andWhere('al.mobile = :mbl');
        }
        if (isset($searchParams['marritalStatus'])) {
            $params[':mrtStat'] = $searchParams['marritalStatus'];
            $qbs->andWhere('al.marritalStatus = :mrtStat');
        }
        
        if (isset($searchParams['nationality'])) {
            $params[':nationality'] = $searchParams['nationality'];
            $qbs->andWhere('al.nationality = :nationality');
        }
        if (isset($searchParams['state'])) {
            $params[':sta'] = $searchParams['state'];
            $qbs->andWhere('al.state = :sta');
        }
        if (isset($searchParams['city'])) {
            $params[':ct'] = $searchParams['city'];
            $qbs->andWhere('al.city = :ct');
        }
        if (isset($searchParams['district'])) {
            $params[':dst'] = $searchParams['district'];
            $qbs->andWhere('al.district = :dst');
        }
        if (isset($searchParams['pincode'])) {
            $params[':pin'] = $searchParams['pincode'];
            $qbs->andWhere('al.pincode = :pin');
        }
        if (isset($searchParams['personalEmailId'])) {
            $params[':pEmail'] = $searchParams['personalEmailId'];
            $qbs->andWhere('al.personalEmailId = :pEmail');
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

    public function processPassword($loginId, $userPasswordFromInput, $rehashIfNeeded = false)
    {
        $om = $this->getOrmEntityMgr();
        $user = $this->fetchAll(array(
            'loginId' => $loginId
        ), false, true)[0];
        $currentHashFromPersistentStorage = $user->getPassword();
        $passwordMacthes = password_verify($userPasswordFromInput, 
            $currentHashFromPersistentStorage);
        if (! $passwordMacthes) {
            return false;
        }
        if ($passwordMacthes && $rehashIfNeeded === false) {
            return true;
        }
        if ($passwordMacthes && $rehashIfNeeded === true) {
            $new_cost = NumberPlay::benchmark();
            $new_password_options = array(
                'cost' => $new_cost
            );
            $passInfo = password_get_info($currentHashFromPersistentStorage);
            $algo = $passInfo['algo'];
            $needRehash = password_needs_rehash($currentHashFromPersistentStorage, $algo, 
                $new_password_options);
            if ($needRehash) {
                $newHash = password_hash($userPasswordFromInput, $algo, $new_password_options);
                $om->getConnection()->beginTransaction();
                try {
                    $user->setPassword($newHash);
                    $om->persist($user);
                    $om->flush();
                    $om->getConnection()->commit();
                } catch (\Exception $e) {
                    $om->getConnection()->rollback();
                }
            }
            
            return true;
        } else {
            return false;
        }
    }
}
