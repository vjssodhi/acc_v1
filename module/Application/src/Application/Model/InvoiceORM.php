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

class InvoiceORM
{

    CONST STUDENT_ENTITY = 'Application\Entity\Student';

    CONST INVOICE_ENTITY = 'Application\Entity\Invoice';

    /**
     *
     * @var EntityManager
     */
    protected $ormEntityMgr;

    /**
     *
     * @var StudentORM
     */
    protected $studentModel;

    /**
     *
     * @return EntityManager $ormEntityMgr
     */
    public function getOrmEntityMgr()
    {
        return $this->ormEntityMgr;
    }

    /**
     *
     * @param \Doctrine\ORM\EntityManager $ormEntityMgr            
     */
    public function setOrmEntityMgr($ormEntityMgr)
    {
        $this->ormEntityMgr = $ormEntityMgr;
    }

    /**
     *
     * @return StudentORM $studentModel
     */
    public function getStudentModel()
    {
        return $this->studentModel;
    }

    /**
     *
     * @param \Application\Model\StudentORM $studentModel            
     */
    public function setStudentModel($studentModel)
    {
        $this->studentModel = $studentModel;
    }
}
