<?php
namespace Application\Resource;

use \Zend\Db\Sql\Expression;
use \Zend\Db\Sql\Select;
use \Application\ResultSet\Translation as ResultSet_Translation;
use \Application\Model\Translation as Model_Translation;

class Translation extends Base
{
    const DEFAULT_ENTRIES_PER_PAGE = 100;

    /**
     * Get all records from "translation" table.
     *
     * @return ResultSet_Translation
     */
    public function fetchAll()
    {
        return $this->tableGateway
            ->select(function (Select $select) {
                $select->order('base_id ASC');
            });
    }

    /**
     * Count translations with given filter.
     *
     * @param string      $locale        Locale to select
     * @param string|null $file          File to select (null = all files)
     * @param boolean     $filterUnclear Filter only unclear translations
     *
     * @return int Number of translations with this filter
     */
    public function countByLanguageAndFile(
        $locale,
        $file          = null,
        $filterUnclear = false
    ) {
        $sql    = $this->tableGateway->getSql();
        $select = $sql->select();
        $select = $this->prepareSqlByLanguageAndFile($select, $locale, $file, $filterUnclear);

        // Add count
        $select->reset(Select::COLUMNS)
            ->columns(array('count' => new Expression('COUNT(*)')));

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();
        $result    = $resultSet->current();

        return (int) $result['count'];
    }

    /**
     * Search all translations by given locale and file.
     *
     * @param string      $locale          Locale to select
     * @param string|null $file            File to select (null = all files)
     * @param boolean     $filterUnclear   Filter only unclear translations
     * @param int|null    $elementsPerPage Entries to show per page (null = all entries)
     * @param int         $page            Page to show
     *
     * @return Model_Translation[]
     */
    public function fetchByLanguageAndFile(
        $locale,
        $file            = null,
        $filterUnclear   = false,
        $elementsPerPage = self::DEFAULT_ENTRIES_PER_PAGE,
        $page            = 1
    ) {
        return $this->tableGateway
            ->select(function (Select $select) use ($locale, $file, $filterUnclear, $elementsPerPage, $page) {
                $this->prepareSqlByLanguageAndFile($select, $locale, $file, $filterUnclear);

                if (null !== $elementsPerPage) {
                    // React to pagination
                    $select->limit((int) $elementsPerPage)
                        ->offset(($page - 1) * ((int) $elementsPerPage));
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
    private function prepareSqlByLanguageAndFile(Select $select, $locale, $file = null, $filterUnclear = false)
    {
        $select->order('translation.translation_id ASC');

        $joinCondition  = $this->tableGateway->getTable() . '.base_id = translation_base.base_id';
        $joinCondition .= ' AND translation.locale = ' . $this->tableGateway->getAdapter()->getPlatform()->quoteValue($locale); // . ' OR locale IS NULL ';

        // quoteInto doesn't exist anymore and $this->adapter->getPlatform()->quoteValue() not working
        $select->join('translation_base', new Expression($joinCondition), array(), Select::JOIN_LEFT);

        if (null !== $file) {
            $select->join(
                'translation_file',
                'translation_base.translation_file_id = translation_file.translation_file_id',
                array()
            );

            $select->where(array('translation_file.filename' => $file));
        }

        if ($filterUnclear) {
//             $select->where(array('translation.unclear_translation' => 1));
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
    public function fetchByBaseId($baseId)
    {
        return $this->tableGateway
            ->select(function (Select $select) use ($baseId) {
                $select->where(array('base_id' => $baseId));
                $select->order('locale ASC');
            });
    }

    /**
     * Get a single record from "translation" table by its record id.
     *
     * @param int $id ID of record
     *
     * @return Model_Translation
     * @throws \Exception
     */
    public function getTranslation($id)
    {
        $record = $this->tableGateway
            ->select(array('translation_id' => (int) $id))
            ->current();

        if (!$record) {
            throw new \Exception('Could not find row <' . $id . '>');
        }

        return $record;
    }

    /**
     * Save or update record.
     *
     * @param Model_Translation $translation Instance
     *
     * @return bool|int ID of record on success, FALSE on failure
     * @throws \Exception
     */
    public function saveTranslation(Model_Translation $translation)
    {
        $data = $translation->toArray();
        $id   = (int) $translation->getTranslationId();

        if ($id === 0) {
            // Insert record
            if (!$this->tableGateway->insert($data)) {
                return false;
            }

            return $this->getLastInsertValue();
        } else {
            if ($this->getTranslation($id)) {
                // Update record
                if (!$this->tableGateway->update($data, array('translation_id' => $id))) {
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
    public function deleteTranslation($id)
    {
        return $this->tableGateway->delete(array('translation_id' => (int) $id));
    }
}
