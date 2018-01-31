<?php
namespace Application\Model;

use \Zend\Db\TableGateway\AbstractTableGateway;
use \Zend\Db\Sql\Select;
use \Application\ResultSet\Suggestion as ResultSet_Suggestion;

/**
 * Class handles access to the "suggestion" table.
 */
class SuggestionTable extends AbstractTableGateway
{
    use Traits\TableConstructor;

    /**
     * Get all records from "suggestion" table.
     *
     * @return ResultSet_Suggestion
     */
    public function fetchAll(): ResultSet_Suggestion
    {
        return $this->tableGateway
            ->select(function (Select $select) {
                $select->order('suggestion_id ASC');
            });
    }

    /**
     * Get all suggestions of a translation.
     *
     * @param int $translationId
     *
     * @return ResultSet_Suggestion
     */
    public function fetchByTranslationId(int $translationId): ResultSet_Suggestion
    {
        return $this->tableGateway
            ->select(function (Select $select) use ($translationId) {
                $select->where([ 'translation_id' => $translationId ]);
                $select->order('suggestion_id ASC');
            });
    }

    /**
     * Get a single record from "suggestion" table by its record id.
     *
     * @param int $id ID of record
     *
     * @return Suggestion
     * @throws \Exception
     */
    public function getSuggestion(int $id): Suggestion
    {
        $record = $this->tableGateway
            ->select([ 'suggestion_id' => (int) $id ])
            ->current();

        if (!$record) {
            throw new \Exception('Could not find row <' . $id . '>');
        }

        return $record;
    }

    /**
     * Save or update record.
     *
     * @param Suggestion $suggestion Instance
     *
     * @return bool|int ID of record on success, FALSE on failure
     * @throws \Exception
     */
    public function saveSuggestion(Suggestion $suggestion)
    {
        $data = $suggestion->toArray();
        $id   = $suggestion->getSuggestionId();

        if ($id === 0) {
            // Insert record
            if (!$this->tableGateway->insert($data)) {
                return false;
            }

            return $this->getLastInsertValue();
        } else {
            if ($this->getSuggestion($id)) {
                // Update record
                if (!$this->tableGateway->update($data, [ 'suggestion_id' => $id ])) {
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
    public function deleteSuggestion(int $id): int
    {
        return $this->tableGateway->delete([ 'suggestion_id' => (int) $id ]);
    }
}