<?php
namespace Application\Model\Interfaces;

interface UserInterface extends TimeLoggerInterface, DoctrineIdInterface
{

    public function getFullName();

    public function setFullName($fullName);

    public function getAccessLevel();

    public function setAccessLevel($accessLevel);

    public function getLoginId();

    public function setLoginId($loginId);

    public function getPassword();

    public function setPassword($password);

    public function getPersonalEmailId();

    public function setPersonalEmailId($personalEmailId);

    public function getDateOfBirth();

    public function setDateOfBirth($dateOfBirth);

    public function getGender();

    public function setGender($gender);

    public function getMobile();

    public function setMobile($mobile);

    public function getMarritalStatus();

    public function setMarritalStatus($marritalStatus);

    public function getNationality();

    public function setNationality($nationality);

    public function getState();

    public function setState($state);

    public function getCity();

    public function setCity($city);

    public function getDistrict();

    public function setDistrict($district);

    public function getPincode();

    public function setPincode($pincode);

    public function getPermanentAddress();

    public function setPermanentAddress($permanentAddress);

    public function getCorrespondenceAddress();

    public function setCorrespondenceAddress($correspondenceAddress);

    public function getImageId();

    public function setImageId($imageId);

    public function getBasicBioComplete();

    public function setBasicBioComplete($basicBioComplete);

    public function getAccountVerified();

    public function setAccountVerified($accountVerified);

    public function getEmailVerified();

    public function setEmailVerified($emailVerified);
}