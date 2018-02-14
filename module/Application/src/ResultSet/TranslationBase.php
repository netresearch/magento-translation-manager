<?php
namespace Application\ResultSet;

use \Zend\Db\ResultSet\ResultSet as ZendResultSet;
use \Application\Model\TranslationBase as Model_TranslationBase;

class TranslationBase extends ZendResultSet
{
    /**
     * Get all record ids.
     *
     * @return int[]
     */
    public function getIds(): array
    {
        $ids = [];

        $this->rewind();
        while ($this->valid()) {
            $ids[] = $this->current()->getId();
            $this->next();
        }

        return $ids;
    }
}
