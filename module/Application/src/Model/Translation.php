<?php
namespace Application\Model;

use Application\Helper\ArrayAccess;

class Translation
{
    /**
     * @var int
     */
    private $translationId = 0;

    /**
     * @var int
     */
    private $baseId = 0;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $currentTranslation;

    /**
     * @var bool
     */
    private $unclearTranslation = true;

    /**
     * This method simply copies the data from the passed in array to our entities properties.
     *
     * @param array $data Data from database
     *
     * @return void
     */
    public function exchangeArray(array $data): void
    {
        $this->translationId      = ArrayAccess::getInt($data, 'translation_id');
        $this->baseId             = ArrayAccess::getInt($data, 'base_id');
        $this->locale             = ArrayAccess::getString($data, 'locale');
        $this->currentTranslation = ArrayAccess::getString($data, 'current_translation');
        $this->unclearTranslation = ArrayAccess::getBool($data, 'unclear_translation', true);
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'translation_id'      => $this->translationId,
            'base_id'             => $this->baseId,
            'locale'              => $this->locale,
            'current_translation' => $this->currentTranslation,
            'unclear_translation' => (int) $this->unclearTranslation,
        ];
    }

    public function getTranslationId(): int
    {
        return $this->translationId;
    }

    public function getBaseId(): int
    {
        return $this->baseId;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getCurrentTranslation(): string
    {
        return (string) $this->currentTranslation;
    }

    public function getUnclearTranslation(): bool
    {
        return $this->unclearTranslation;
    }
}
