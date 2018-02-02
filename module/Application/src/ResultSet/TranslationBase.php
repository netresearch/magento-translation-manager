<?php
namespace Application\ResultSet;

use \Zend\Db\ResultSet\ResultSet as ZendResultSet;
use \Application\Model\TranslationBase as Model_TranslationBase;

class TranslationBase extends ZendResultSet
{
    /**
     * Get record by id.
     *
     * @param int $id Record id
     *
     * @return Model_TranslationBase
     */
    public function getById(int $id): Model_TranslationBase
    {
        while ($this->valid()) {
            if ($this->current()->getId() === $id) {
                return $this->current();
            }

            $this->next();
        }

        return new Model_TranslationBase();
    }

    /**
     * Get all record ids.
     *
     * @return int[]
     */
    public function getIds(): array
    {
        $ids = [];

        while ($this->valid()) {
            $ids[] = $this->current()->getId();
            $this->next();
        }

        return $ids;
    }
}
