<?php
namespace Application\Resource;

use \Zend\Db\TableGateway\AbstractTableGateway;
use \Zend\Db\Sql\Select;
use \Application\ResultSet\SupportedLocale as ResultSet_SupportedLocale;

/**
 * Class handles access to the "supported_locale" table.
 */
class SupportedLocale extends AbstractTableGateway
{
    use Traits\ResourceConstructor;

    /**
     * Get all records from "supported_locale" table.
     *
     * @return ResultSet_SupportedLocale
     */
    public function fetchAll(): ResultSet_SupportedLocale
    {
        return $this->tableGateway
            ->select(function (Select $select) {
                $select->order('locale ASC');
            });
    }
}