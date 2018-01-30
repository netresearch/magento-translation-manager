<?php
namespace Application\Resource;

use \Zend\Db\TableGateway\AbstractTableGateway;
use \Zend\Db\Sql\Select;
use \Application\ResultSet\TranslationBase as ResultSet_TranslationBase;
use \Application\Model\TranslationBase as Model_TranslationBase;

/**
 * Class handles access to the "translation_base" table.
 */
class TranslationBase extends AbstractTableGateway
{
    use Traits\ResourceConstructor;

    /**
     * Get all records from "translation_base" table.
     *
     * @return ResultSet_TranslationBase
     */
    public function fetchAll(): ResultSet_TranslationBase
    {
        return $this->tableGateway
            ->select(function (Select $select) {
                $select->order('base_id ASC');
            });
    }

    /**
     * Get a single record from "translation_base" table by its record id.
     *
     * @param int $id ID of record
     *
     * @return Model_TranslationBase
     * @throws \Exception
     */
    public function getTranslationBase($id): Model_TranslationBase
    {
        $record = $this->tableGateway
            ->select([ 'base_id' => (int) $id ])
            ->current();

        if (!$record) {
            throw new \Exception('Could not find row <' . $id . '>');
        }

        return $record;
    }

    /**
     * Save or update record.
     *
     * @param Model_TranslationBase $translationBase Instance
     *
     * @return bool|int ID of record on success, FALSE on failure
     * @throws \Exception
     */
    public function saveTranslationBase(Model_TranslationBase $translationBase)
    {
        $data = $translationBase->toArray();
        $id   = (int) $translationBase->getBaseId();

        if ($id === 0) {
            // Insert record
            if (!$this->tableGateway->insert($data)) {
                return false;
            }

            return $this->getLastInsertValue();
        } else {
            if ($this->getTranslationBase($id)) {
                // Update record
                if (!$this->tableGateway->update($data, [ 'base_id' => $id ])) {
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
    public function deleteTranslationBase($id): int
    {
        return $this->tableGateway->delete([ 'base_id' => (int) $id ]);
    }
}
