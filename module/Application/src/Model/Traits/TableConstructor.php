<?php
namespace Application\Model\Traits;

use \Zend\Db\TableGateway\AbstractTableGateway;

trait TableConstructor
{
    /**
     * @var AbstractTableGateway
     */
    private $tableGateway;

    /**
     * Constructor.
     *
     * @param AbstractTableGateway $tableGateway Table gateway interface
     */
    public function __construct(AbstractTableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
}
