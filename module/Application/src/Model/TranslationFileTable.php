<?php
namespace Application\Model;

use \Zend\Db\TableGateway\AbstractTableGateway;
use \Zend\Db\Sql\Select;
use \Application\ResultSet\TranslationFile as ResultSet_TranslationFile;

/**
 * Class handles access to the "translation_file" table.
 */
class TranslationFileTable extends AbstractTableGateway
{
    use Traits\TableConstructor;

    /**
     * Get all records from "translation_file" table.
     *
     * @return ResultSet_TranslationFile
     */
    public function fetchAll(): ResultSet_TranslationFile
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
     * @return TranslationFile
     * @throws \Exception
     */
    public function getTranslationFile(int $id): TranslationFile
    {
        $record = $this->tableGateway
            ->select([ 'translation_file_id' => (int) $id ])
            ->current();

        if (!$record) {
            throw new \Exception('Could not find row <' . $id . '>');
        }

        return $record;
    }
}
