<?php
namespace Application\Model;

use Application\Helper\ArrayAccess;

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
        $this->locale = ArrayAccess::getString($data, 'locale');
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'locale' => $this->locale,
        ];
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
