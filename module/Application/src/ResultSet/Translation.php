<?php
namespace Application\ResultSet;

use \Zend\Db\ResultSet\ResultSet as ZendResultSet;
use \Locale\ResultSet\SupportedLocale as ResultSet_SupportedLocale;
use \Locale\Model\SupportedLocale as Model_SupportedLocale;
use \Application\ResultSet;
use \Application\Model;

class Translation extends ZendResultSet
{
    /**
     * @param ResultSet_SupportedLocale $supportedLocales
     *
     * @return Translation[]
     */
    public function groupByLocales(ResultSet_SupportedLocale $supportedLocales): array
    {
        $languages = [];

        /** @var Model\Translation $record */
        foreach ($this as $record) {
            $languages[$record->getLocale()] = $record;
        }

        /** @var Model_SupportedLocale $record */
        foreach ($supportedLocales as $record) {
            if (!array_key_exists($record->getLocale(), $languages)) {
                $languages[$record->getLocale()] = new Model\Translation();
            }
        }

        return $languages;
    }
}
