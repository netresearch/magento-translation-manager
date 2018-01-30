<?php
namespace Application\Model;

use Application\Helper\ArrayAccess;

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
        $this->suggestionId         = ArrayAccess::getInt($data, 'suggestion_id');
        $this->translationId        = ArrayAccess::getInt($data, 'translation_id');
        $this->suggestedTranslation = ArrayAccess::getString($data, 'suggested_translation');
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
