<?php
namespace Application\Resource;

use \Zend\Db\Sql\Select;
use \Application\ResultSet\TranslationFile as ResultSet_TranslationFile;
use \Application\Model\TranslationFile as Model_TranslationFile;

class TranslationFile extends Base
{
    /**
     * Get all records from "translation_file" table.
     *
     * @return ResultSet_TranslationFile
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
     * @return Model_TranslationFile
     * @throws \Exception
     */
    public function getTranslationFile($id)
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
