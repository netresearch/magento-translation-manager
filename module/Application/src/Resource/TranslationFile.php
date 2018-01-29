<?php
namespace Application\Resource;

use Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class TranslationFile extends Base
{
    /**
     * Get all records from "translation_file" table.
     *
     * @return \Application\ResultSet\TranslationFile
     */
    public function fetchAll()
    {
        return $this->tableGateway
            ->select(function (Select $select) {
                $select->order('filename ASC');
            });
    }

    /**
     * Get a single record from "translation_file" table by its record id.
     *
     * @param int $id ID of record
     *
     * @return \Application\Model\TranslationFile
     * @throws \Exception
     */
    public function getTranslationFile($id)
    {
        $record = $this->tableGateway
            ->select(array('translation_file_id' => (int) $id))
            ->current();

        if (!$record) {
            throw new \Exception('Could not find row <' . $id . '>');
        }

        return $record;
    }
}
