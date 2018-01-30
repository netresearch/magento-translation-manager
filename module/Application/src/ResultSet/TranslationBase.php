<?php
namespace Application\ResultSet;

use \Zend\Db\ResultSet\ResultSet as ZendResultSet;
use \Application\Model;

class TranslationBase extends ZendResultSet
{
    /**
     * Get record by id.
     *
     * @param int $id Record id
     *
     * @return Model\TranslationBase
     */
    public function getById($id): Model\TranslationBase
    {
        while ($this->valid()) {
            if ($this->current()->getBaseId() === $id) {
                return $this->current();
            }

            $this->next();
        }

        return new Model\TranslationBase();
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
            $ids[] = $this->current()->getBaseId();
            $this->next();
        }

        return $ids;
    }
}
