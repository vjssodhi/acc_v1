<?php
namespace Application\Model\Interfaces;

interface TimeLoggerInterface
{

    public function getUpdatedOn();

    public function setUpdatedOn($updatedOn);

    public function getCreatedOn();

    public function setCreatedOn($createdOn);

    public function logDatesOnCreate();

    public function logDatesOnUpdate();
}