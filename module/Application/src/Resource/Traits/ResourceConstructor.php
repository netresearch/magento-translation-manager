<?php
namespace Application\Resource\Traits;

use \Zend\Db\TableGateway\TableGateway;

trait ResourceConstructor
{
    /**
     * @var TableGateway
     */
    private $tableGateway;

    /**
     * Constructor.
     *
     * @param TableGateway $tableGateway Table gateway instance
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
}
