<?php
namespace Application\Model\Traits;

use \Zend\Db\TableGateway\TableGatewayInterface;

trait TableConstructor
{
    /**
     * @var TableGatewayInterface
     */
    private $tableGateway;

    /**
     * Constructor.
     *
     * @param TableGatewayInterface $tableGateway Table gateway interface
     */
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
}
