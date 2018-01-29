<?php

namespace Application\Model;

class Suggestion
{
    /**
     * @var int
     */
    private $suggestionId;

    /**
     * @var int
     */
    private $translationId;

    /**
     * @var string
     */
    private $suggestedTranslation;

    /**
     * This method simply copies the data from the passed in array to our entities properties.
     *
     * @param array $data Data from database
     *
     * @return void
     */
    public function exchangeArray(array $data)
    {
        $this->suggestionId         = (!empty($data['suggestion_id'])) ? ((int) $data['suggestion_id']) : null;
        $this->translationId        = (!empty($data['translation_id'])) ? ((int) $data['translation_id']) : null;
        $this->suggestedTranslation = (!empty($data['suggested_translation'])) ? $data['suggested_translation'] : null;
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'suggestion_id'         => $this->suggestionId,
            'translation_id'        => $this->translationId,
            'suggested_translation' => $this->suggestedTranslation,
        ];
    }

    /**
     * @return int
     */
    public function getSuggestionId()
    {
        return $this->suggestionId;
    }

    /**
     * @return int
     */
    public function getTranslationId()
    {
        return $this->translationId;
    }

    /**
     * @return string
     */
    public function getSuggestedTranslation()
    {
        return $this->suggestedTranslation;
    }
}
