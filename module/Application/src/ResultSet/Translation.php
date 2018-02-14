<?php
namespace Application\ResultSet;

use \Zend\Db\ResultSet\ResultSet;
use \Application\Model\Translation as Model_Translation;

class Translation extends ResultSet
{
    /**
     * @param ResultSet $locales
     *
     * @return Model_Translation[]
     */
    public function groupByLocales(ResultSet $locales): array
    {
        $languages = [];

        /** @var Model_Translation $record */
        foreach ($this as $record) {
            $languages[$record->getLocale()] = $record;
        }

        /** @var Locale $record */
        foreach ($locales as $record) {
            if (!array_key_exists($record->getLocale(), $languages)) {
                $languages[$record->getLocale()] = new Model_Translation();
            }
        }

        return $languages;
    }
}
