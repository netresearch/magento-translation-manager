<?php
namespace Application\Resource;

use Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class SupportedLocale extends Base
{
    /**
     * Get all records from "supported_locale" table.
     *
     * @return \Application\ResultSet\SupportedLocale
     */
    public function fetchAll()
    {
        return $this->tableGateway
            ->select(function (Select $select) {
                $select->order('locale ASC');
            });
    }
}