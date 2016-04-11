<?php
namespace Application\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;
use Application\Model\UserORM;
use Application\Utilities\NumberPlay;

class EmpAuthAdapter implements AdapterInterface, ServiceLocatorAwareInterface
{

    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $services;

    protected $loginId;

    protected $password;

    protected $error;

    protected $authEntityType;

    protected $identity;

    /**
     *
     * @return the $userInfoContainer
     */
    public function getUserInfoContainer()
    {
        return $this->userInfoContainer;
    }

    /**
     *
     * @param \Zend\Session\Container $userInfoContainer            
     */
    public function setUserInfoContainer($userInfoContainer)
    {
        $this->userInfoContainer = $userInfoContainer;
    }

    /**
     *
     * @return the $loginId
     */
    public function getLoginId()
    {
        return $this->loginId;
    }

    /**
     *
     * @var Container
     */
    protected $userInfoContainer;

    /**
     *
     * @param field_type $loginId            
     */
    public function setLoginId($loginId)
    {
        $this->loginId = $loginId;
    }

    /**
     *
     * @return the $authEntityType
     */
    public function getAuthEntityType()
    {
        return $this->authEntityType;
    }

    /**
     *
     * @param field_type $authEntityType            
     */
    public function setAuthEntityType($authEntityType)
    {
        $this->authEntityType = $authEntityType;
    }

    /**
     *
     * @return the $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     *
     * @return the $error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     *
     * @return the $identity
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->services = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->services;
    }

    /**
     *
     * @param field_type $password            
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     *
     * @param field_type $error            
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     *
     * @param field_type $identity            
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * Sets username and password for authentication
     *
     * @return void
     */
    public function __construct($loginId, $password = null)
    {
        $this->loginId = $loginId;
        $this->password = $password;
        $userInfoContainer = new Container(USER_INFO_CONTAINER_NAME);
        $this->userInfoContainer = $userInfoContainer;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If
     *         authentication cannot be performed
     */
    public function authenticate()
    {
        /* @var $modelAccessor UserORM */
        $modelAccessor = $this->getServiceLocator()->get('UserModel');
        $problems = false;
        $userLoginId = $this->getLoginId();
        $password = $this->getPassword();
        $userId = false;
        
        $userInfo = $modelAccessor->fetchAll(array(
            'loginId' => $userLoginId
        ))[0];
        if (! empty($userInfo)) {
            $userId = $userInfo['id'];
            $problems = false;
        } else {
            $problems = true;
            $error = 'UserName or/And Password is Invalid';
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null, 
                array(
                    $error
                ));
        }
        
        if (! $problems) {
            try {
                $userIsGenuine = $modelAccessor->processPassword($userLoginId, $password, true);
            } catch (\Exception $e) {
                $error = 'Failed to check credentials';
                return new Result(Result::FAILURE, null, 
                    array(
                        $error
                    ));
            }
        }
        
        if ($userIsGenuine) {
            $this->getUserInfoContainer()->allInfo = $userInfo;
            $this->getUserInfoContainer()->accessLevel = $userInfo['accessLevel'];
            $existingUsers = $modelAccessor->fetchAll(null, false, false, 
                array(
                    'id',
                    'personalEmailId'
                ));
            $infoToCache = array();
            if (! empty($existingUsers)) {
                foreach ($existingUsers as $info) {
                    $infoToCache[$info['id']] = $info['personalEmailId'];
                }
            }
            $fileName = NumberPlay::encfilecontents($infoToCache, ENC_KEY);
            $this->getUserInfoContainer()->allEncUsers = $fileName;
            $this->getUserInfoContainer()->imageId = $userInfo['imageId'];
            $this->getUserInfoContainer()->basicBioComplete = $userInfo['basicBioComplete'];
            $this->setIdentity($userId);
            $error = 'Logged in Successfully';
            return new Result(Result::SUCCESS, $userId, array(
                $error
            ));
        }
        
        $error = 'UserName or/And Password is Invalid';
        return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, 
            array(
                $error
            ));
    }
}
