<?php

namespace Application\Model;

class Translation
{
    /**
     * @var int
     */
    private $translationId;

    /**
     * @var int
     */
    private $baseId;

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
    private $unclearTranslation;

    /**
     * This method simply copies the data from the passed in array to our entities properties.
     *
     * @param array $data Data from database
     *
     * @return void
     */
    public function exchangeArray(array $data)
    {
        $this->translationId      = (!empty($data['translation_id'])) ? ((int) $data['translation_id']) : null;
        $this->baseId             = (!empty($data['base_id'])) ? ((int) $data['base_id']) : null;
        $this->locale             = (!empty($data['locale'])) ? $data['locale'] : null;
        $this->currentTranslation = (!empty($data['current_translation'])) ? $data['current_translation'] : null;
        $this->unclearTranslation = (!empty($data['unclear_translation'])) ? ((bool) $data['unclear_translation']) : null;
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'translation_id'      => $this->translationId,
            'base_id'             => $this->baseId,
            'locale'              => $this->locale,
            'current_translation' => $this->currentTranslation,
            'unclear_translation' => (int) $this->unclearTranslation,
        ];
    }

    public function getTranslationId()
    {
        return $this->translationId;
    }

    public function getBaseId()
    {
        return $this->baseId;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getCurrentTranslation()
    {
        return $this->currentTranslation;
    }

    public function getUnclearTranslation()
    {
        return $this->unclearTranslation;
    }
}
