<?php
namespace Application\Model;

use \Zend\Db\TableGateway\AbstractTableGateway;
use \Zend\Db\Sql\Expression;
use \Zend\Db\Sql\Select;
use \Application\ResultSet\Translation as ResultSet_Translation;

/**
 * Class handles access to the "translation" table.
 */
class TranslationTable extends AbstractTableGateway
{
    use Traits\TableConstructor;

    /**
     * Get all records from "translation" table.
     *
     * @return ResultSet_Translation
     */
    public function fetchAll(): ResultSet_Translation
    {
        return $this->tableGateway
            ->select(function (Select $select) {
                $select->order('baseId ASC');
            });
    }

    /**
     * Get all records by the given translation and base record id.
     *
     * @param string $translation Translated value
     * @param int    $baseId      Base record id
     *
     * @return ResultSet_Translation
     */
    public function fetchByTranslationAndBaseId(string $translation, int $baseId): ResultSet_Translation
    {
        return $record = $this->tableGateway
            ->select([
                'translation' => $translation,
                'baseId'      => $baseId,
            ]);
    }

    /**
     * Count translations with given filter.
     *
     * @param string      $locale        Locale to select
     * @param string|null $file          File to select (null = all files)
     * @param bool        $filterUnclear Filter only unclear translations
     *
     * @return int Number of translations with this filter
     */
    public function countByLanguageAndFile(
        string  $locale,
        ?string $file          = null,
        bool    $filterUnclear = false
    ): int {
        $sql    = $this->tableGateway->getSql();
        $select = $sql->select();
        $select = $this->prepareSqlByLanguageAndFile($select, $locale, $file, $filterUnclear);

        // Add count
        $select->reset(Select::COLUMNS)
            ->columns([ 'count' => new Expression('COUNT(*)') ]);

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();
        $result    = $resultSet->current();

        return $result['count'];
    }

    /**
     * Search all translations by given locale and file.
     *
     * @param string      $locale          Locale to select
     * @param string|null $file            File to select (null = all files)
     * @param bool        $filterUnclear   Filter only unclear translations
     * @param int|null    $elementsPerPage Entries to show per page (null = all entries)
     * @param int         $page            Page to show
     *
     * @return ResultSet_Translation
     */
    public function fetchByLanguageAndFile(
        string  $locale,
        ?string $file            = null,
        bool    $filterUnclear   = false,
        ?int    $elementsPerPage = 25,
        int     $page            = 1
    ): ResultSet_Translation {
        return $this->tableGateway
            ->select(function (Select $select) use ($locale, $file, $filterUnclear, $elementsPerPage, $page) {
                $this->prepareSqlByLanguageAndFile($select, $locale, $file, $filterUnclear);

                if (null !== $elementsPerPage) {
                    // React to pagination
                    $select->limit($elementsPerPage)
                        ->offset(($page - 1) * $elementsPerPage);
                }
            });
    }

    /**
     * Prepare base SQL for grid filtered by language and file.
     *
     * @param Select      $select        Empty Select object
     * @param string      $locale        Locale to filter
     * @param string|null $file          Filename to filter
     * @param bool        $filterUnclear Filter only unclear translations
     *
     * @return Select Prepared query
     */
    private function prepareSqlByLanguageAndFile(
        Select  $select,
        string  $locale,
        ?string $file = null,
        bool    $filterUnclear = false
    ): Select {
        $select->order('b.originSource ASC');

        $joinCondition  = $this->tableGateway->getTable() . '.baseId = b.id';
        $joinCondition .= ' AND translation.locale = ' . $this->tableGateway->getAdapter()->getPlatform()->quoteValue($locale); // . ' OR locale IS NULL ';

        // quoteInto doesn't exist anymore and $this->adapter->getPlatform()->quoteValue() not working
        $select->join(['b' => 'translationBase'], new Expression($joinCondition), [ 'originSource', 'baseId' => 'id', 'fileId' ], Select::JOIN_RIGHT);

        if (null !== $file) {
            $select->join(
                'translationFile',
                'b.fileId = translationFile.id',
                []
            );

            $select->where([ 'translationFile.filename' => $file ]);
        }

        if ($filterUnclear) {
            $select->where([ 'translation.unclear' => 1 ]);
        }

        return $select;
    }

    /**
     * Get translated strings of base translation ordered by locale.
     *
     * @param int $baseId
     *
     * @return ResultSet_Translation
     */
    public function fetchByBaseId(int $baseId): ResultSet_Translation
    {
        return $this->tableGateway
            ->select(function (Select $select) use ($baseId) {
                $select->where([ 'baseId' => $baseId ]);
                $select->order('locale ASC');
            });
    }

    /**
     * Get a single record from "translation" table by its record id.
     *
     * @param int $id ID of record
     *
     * @return Translation
     * @throws \Exception
     */
    public function getTranslation(int $id): Translation
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
     * @param Translation $translation Instance
     *
     * @return bool|int ID of record on success, FALSE on failure
     * @throws \Exception
     */
    public function saveTranslation(Translation $translation)
    {
        $data = $translation->toArray();
        $id   = $translation->getId();

        if ($id === 0) {
            // Insert record
            if (!$this->tableGateway->insert($data)) {
                return false;
            }

            return (int) $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getTranslation($id)) {
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
    public function deleteTranslation(int $id): int
    {
        return $this->tableGateway->delete([ 'id' => $id ]);
    }
}
