<?php
namespace Application\ResultSet;

use \Zend\Db\ResultSet\ResultSet as ZendResultSet;
use \Application\ResultSet\Locale as ResultSet_Locale;
use \Application\Model\Translation as Model_Translation;
use \Application\Model\Locale;

class Translation extends ZendResultSet
{
    /**
     * @param ResultSet_Locale $locales
     *
     * @return Model_Translation[]
     */
    public function groupByLocales(ResultSet_Locale $locales): array
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

//     /**
//      * @param @param string $locale Locale string, e.g. de_DE
//      *
//      * @return Model_Translation
//      */
//     public function getByLocale(string $locale): Model_Translation
//     {
//         /** @var Model_Translation $record */
//         foreach ($this as $record) {
//             if ($record->getLocale() === $locale) {
//                 return $record;
//             }
//         }

//         return new Model_Translation();
//     }
}
