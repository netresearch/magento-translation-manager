<?php
namespace Locale\Model;

use \Zend\Db\TableGateway\AbstractTableGateway;
use \Zend\Db\Sql\Select;
use \Application\Model\Traits;
use \Locale\ResultSet\SupportedLocale as ResultSet_SupportedLocale;

/**
 * Class handles access to the "supported_locale" table.
 */
class SupportedLocaleTable extends AbstractTableGateway
{
    use Traits\TableConstructor;

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

    /**
     * Get a single record from "supported_locale" table by its record id.
     *
     * @param int $id ID of record
     *
     * @return SupportedLocale
     * @throws \Exception
     */
    public function getSupportedLocale(int $id): SupportedLocale
    {
        $record = $this->tableGateway
            ->select([ 'id' => (int) $id ])
            ->current();

        if (!$record) {
            throw new \Exception('Could not find row <' . $id . '>');
        }

        return $record;
    }

    /**
     * Save or update record.
     *
     * @param SupportedLocale $supportedLocale Instance
     *
     * @return bool|int ID of record on success, FALSE on failure
     * @throws \Exception
     */
    public function saveSupportedLocale(SupportedLocale $supportedLocale)
    {
        $data = $supportedLocale->toArray();
        $id   = $supportedLocale->getId();

        if ($id === 0) {
            // Insert record
            if (!$this->tableGateway->insert($data)) {
                return false;
            }

            return $this->getLastInsertValue();
        } else {
            if ($this->getSupportedLocale($id)) {
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
    public function deleteSupportedLocale(int $id): int
    {
        return $this->tableGateway->delete([ 'id' => (int) $id ]);
    }
}