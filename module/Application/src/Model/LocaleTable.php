<?php
namespace Application\Model;

use \Zend\Db\Sql\Select;
use \Zend\Db\ResultSet\ResultSet;

/**
 * Class handles access to the "locale" table.
 */
class LocaleTable
{
    use Traits\TableConstructor;

    /**
     * Get all records from "locale" table.
     *
     * @return ResultSet
     */
    public function fetchAll(): ResultSet
    {
        return $this->tableGateway
            ->select(function (Select $select) {
                $select->order('locale ASC');
            });
    }

    /**
     * Get a single record from "locale" table by its record id.
     *
     * @param int $id ID of record
     *
     * @return Locale
     * @throws \Exception
     */
    public function getLocale(int $id): Locale
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
     * Get a single record from "locale" table by its record id.
     *
     * @param string $locale Locale string, e.g. de_DE
     *
     * @return Locale
     * @throws \Exception
     */
    public function getLocaleByLocale(string $locale): Locale
    {
        $record = $this->tableGateway
            ->select([ 'locale' => $locale ])
            ->current();

        if (!$record) {
            throw new \Exception('Could not find row <' . $id . '>');
        }

        return $record;
    }

    /**
     * Save or update record.
     *
     * @param Locale $locale Instance
     *
     * @return bool|int ID of record on success, FALSE on failure
     * @throws \Exception
     */
    public function saveLocale(Locale $locale)
    {
        $data = $locale->toArray();
        $id   = $locale->getId();

        if ($id === 0) {
            // Insert record
            if (!$this->tableGateway->insert($data)) {
                return false;
            }

            return (int) $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getLocale($id)) {
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
    public function deleteLocale(int $id): int
    {
        return $this->tableGateway->delete([ 'id' => $id ]);
    }
}