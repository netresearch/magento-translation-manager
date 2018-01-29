<?php
namespace Application\ResultSet;

use \Zend\Db\ResultSet\ResultSet as ZendResultSet;
use \Application\ResultSet;
use \Application\Model;

class Translation extends ZendResultSet
{
    /**
     * @param ResultSet\SupportedLocale $supportedLocales
     *
     * @return Translation[]
     */
    public function groupByLocales(ResultSet\SupportedLocale $supportedLocales)
    {
        $languages = array();

        /** @var Model\Translation $record */
        foreach ($this as $record) {
            $languages[$record->getLocale()] = $record;
        }

        /** @var Model\SupportedLocale $record */
        foreach ($supportedLocales as $record) {
            if (!array_key_exists($record->getLocale(), $languages)) {
                $languages[$record->getLocale()] = new Model\Translation();
            }
        }

        return $languages;
    }
}
