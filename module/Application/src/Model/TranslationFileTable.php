<?php
namespace Application\Model;

use \Zend\Db\Sql\Select;
use \Application\ResultSet\TranslationFile as ResultSet_TranslationFile;

/**
 * Class handles access to the "translationFile" table.
 */
class TranslationFileTable
{
    use Traits\TableConstructor;

    /**
     * Get all records from "translationFile" table.
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
     * Get a record by the given file name.
     *
     * @param string $filename File name
     *
     * @return TranslationFile
     */
    public function fetchByFilename(string $filename): TranslationFile
    {
        $record = $this->tableGateway
            ->select([ 'filename' => $filename ])
            ->current();

        if (!$record) {
            throw new \Exception('Could not find row <' . $filename . '>');
        }

        return $record;
    }

    /**
     * Get a single record from "translationFile" table by its record id.
     *
     * @param int $id ID of record
     *
     * @return TranslationFile
     * @throws \Exception
     */
    public function getTranslationFile(int $id): TranslationFile
    {
        $record = $this->tableGateway
            ->select([ 'id' => $id ])
            ->current();

        if (!$record) {
            throw new \Exception('Could not find row <' . $id . '>');
        }

        return $record;
    }

    /**
     * Save or update record.
     *
     * @param TranslationFile $translationFile Instance
     *
     * @return bool|int ID of record on success, FALSE on failure
     * @throws \Exception
     */
    public function saveTranslationFile(TranslationFile $translationFile)
    {
        $data = $translationFile->toArray();
        $id   = $translationFile->getId();

        if ($id === 0) {
            // Insert record
            if (!$this->tableGateway->insert($data)) {
                return false;
            }

            return (int) $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getTranslationFile($id)) {
                // Update record
                if (!$this->tableGateway->update($data, [ 'id' => $id ])) {
                    return false;
                }

                return $id;
            } else {
                throw new \Exception('Record id does not exist');
            }
        }
    }
}
