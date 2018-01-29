<?php
namespace Application\Resource;

use \Zend\Db\TableGateway\TableGateway;
use \Zend\Db\TableGateway\AbstractTableGateway;

class Base extends AbstractTableGateway
{
    /**
     * @var TableGateway
     */
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
}
