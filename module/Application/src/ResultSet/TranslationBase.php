<?php
namespace Application\ResultSet;

use Zend\Db\ResultSet\ResultSet;

class TranslationBase extends ResultSet
{
    /**
     * Get record by id.
     *
     * @param int $id Record id
     *
     * @return \Application\Model\TranslationBase
     */
    public function getById($id)
    {
        while ($this->valid()) {
            if ($this->current()->getBaseId() === $id) {
                return $this->current();
            }

            $this->next();
        }
    }

    /**
     * Get all record ids.
     *
     * @return int[]
     */
    public function getIds()
    {
        $ids = array();

        while ($this->valid()) {
            $ids[] = $this->current()->getBaseId();
            $this->next();
        }

        return $ids;
    }
}
