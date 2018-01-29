<?php

namespace Application\Model;

class SupportedLocale
{
    /**
     * @var string
     */
    private $locale;

    /**
     * This method simply copies the data from the passed in array to our entities properties.
     *
     * @param array $data Data from database
     *
     * @return void
     */
    public function exchangeArray(array $data)
    {
        $this->locale = (!empty($data['locale'])) ? $data['locale'] : null;
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'locale' => $this->locale,
        ];
    }

    public function getLocale()
    {
        return $this->locale;
    }
}
