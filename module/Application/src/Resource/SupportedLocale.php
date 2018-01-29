<?php
namespace Application\Resource;

use \Zend\Db\Sql\Select;
use \Application\ResultSet\SupportedLocale as ResultSet_SupportedLocale;

class SupportedLocale extends Base
{
    /**
     * Get all records from "supported_locale" table.
     *
     * @return ResultSet_SupportedLocale
     */
    public function fetchAll()
    {
        return $this->tableGateway
            ->select(function (Select $select) {
                $select->order('locale ASC');
            });
    }
}