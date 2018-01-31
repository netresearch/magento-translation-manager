<?php
namespace Application\ResultSet;

use \Zend\Db\ResultSet\ResultSet as ZendResultSet;
use \Application\ResultSet\SupportedLocale as ResultSet_SupportedLocale;
use \Application\Model\Translation as Model_Translation;
use \Application\Model\SupportedLocale;

class Translation extends ZendResultSet
{
    /**
     * @param ResultSet_SupportedLocale $supportedLocales
     *
     * @return Model_Translation[]
     */
    public function groupByLocales(ResultSet_SupportedLocale $supportedLocales): array
    {
        $languages = [];

        /** @var Translation $record */
        foreach ($this as $record) {
            $languages[$record->getLocale()] = $record;
        }

        /** @var SupportedLocale $record */
        foreach ($supportedLocales as $record) {
            if (!array_key_exists($record->getLocale(), $languages)) {
                $languages[$record->getLocale()] = new Model\Translation();
            }
        }

        return $languages;
    }
}
