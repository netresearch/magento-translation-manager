<?php
namespace Application\ResultSet;

use \Zend\Db\ResultSet\ResultSet as ZendResultSet;
use \Application\ResultSet\Locale as ResultSet_Locale;
use \Application\Model\Translation as Model_Translation;
use \Application\Model\Locale;

class Translation extends ZendResultSet
{
    /**
     * @param ResultSet_Locale $supportedLocales
     *
     * @return Model_Translation[]
     */
    public function groupByLocales(ResultSet_Locale $supportedLocales): array
    {
        $languages = [];

        /** @var Translation $record */
        foreach ($this as $record) {
            $languages[$record->getLocale()] = $record;
        }

        /** @var Locale $record */
        foreach ($supportedLocales as $record) {
            if (!array_key_exists($record->getLocale(), $languages)) {
                $languages[$record->getLocale()] = new Model\Translation();
            }
        }

        return $languages;
    }
}
