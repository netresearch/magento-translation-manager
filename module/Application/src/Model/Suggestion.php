<?php

namespace Application\Model;

class Suggestion
{
    /**
     * @var int
     */
    private $suggestionId = 0;

    /**
     * @var int
     */
    private $translationId = 0;

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
        $this->suggestionId         = (int) (!empty($data['suggestion_id'])) ? $data['suggestion_id'] : 0;
        $this->translationId        = (int) (!empty($data['translation_id'])) ? $data['translation_id'] : 0;
        $this->suggestedTranslation = (!empty($data['suggested_translation'])) ? $data['suggested_translation'] : null;
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray(): array
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
    public function getSuggestionId(): int
    {
        return $this->suggestionId;
    }

    /**
     * @return int
     */
    public function getTranslationId(): int
    {
        return $this->translationId;
    }

    /**
     * @return string
     */
    public function getSuggestedTranslation(): string
    {
        return $this->suggestedTranslation;
    }
}
