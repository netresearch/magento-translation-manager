<?php
namespace Application\Model;

use \Zend\Db\TableGateway\AbstractTableGateway;
use \Zend\Db\Sql\Select;
use \Application\ResultSet\TranslationBase as ResultSet_TranslationBase;

/**
 * Class handles access to the "translationBase" table.
 */
class TranslationBaseTable extends AbstractTableGateway
{
    use Traits\TableConstructor;

    /**
     * Get all records from "translationBase" table.
     *
     * @return ResultSet_TranslationBase
     */
    public function fetchAll(): ResultSet_TranslationBase
    {
        return $this->tableGateway
            ->select(function (Select $select) {
                $select->order('id ASC');
            });
    }

    /**
     * Get a record by the given origin source and file id.
     *
     * @param string $originSource Origin source
     * @param int    $fileId       File record id
     *
     * @return TranslationBase
     */
    public function fetchByOriginSourceAndFileId(string $originSource, int $fileId): TranslationBase
    {
        $record = $this->tableGateway
            ->select([
                'originSource' => $originSource,
                'fileId'       => $fileId,
            ])
            ->current();

        if (!$record) {
            throw new \Exception(sprintf('Could not find row <%d, %s>', $fileId, $originSource));
        }

        return $record;
    }

    /**
     * Get a single record from "translationBase" table by its record id.
     *
     * @param int $id ID of record
     *
     * @return TranslationBase
     * @throws \Exception
     */
    public function getTranslationBase(int $id): TranslationBase
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
     * @param TranslationBase $translationBase Instance
     *
     * @return bool|int ID of record on success, FALSE on failure
     * @throws \Exception
     */
    public function saveTranslationBase(TranslationBase $translationBase)
    {
        $data = $translationBase->toArray();
        $id   = $translationBase->getId();

        if ($id === 0) {
            // Insert record
            if (!$this->tableGateway->insert($data)) {
                return false;
            }

            return (int) $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getTranslationBase($id)) {
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

    /**
     * Delete record by ID.
     *
     * @param int $id Record id
     *
     * @return int Number of deleted records (should be one, because of PK)
     */
    public function deleteTranslationBase(int $id): int
    {
        return $this->tableGateway->delete([ 'id' => $id ]);
    }
}
