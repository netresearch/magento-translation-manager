<?php
namespace Application\ResultSet;

use Zend\Db\ResultSet\ResultSet;

class Translation extends ResultSet
{
    /**
     * @param \Application\ResultSet\SupportedLocale $supportedLocales
     *
     * @return \Application\Model\Translation[]
     */
    public function groupByLocales(\Application\ResultSet\SupportedLocale $supportedLocales)
    {
        $languages = array();

        /** @var \Application\Model\Translation $record */
        foreach ($this as $record) {
            $languages[$record->getLocale()] = $record;
        }

        /** @var \Application\Model\SupportedLocale $record */
        foreach ($supportedLocales as $record) {
            if (!array_key_exists($record->getLocale(), $languages)) {
                $languages[$record->getLocale()] = new \Application\Model\Translation();
            }
        }

        return $languages;
    }
}
