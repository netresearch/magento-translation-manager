<?php
namespace Application\Model;

use \Application\Helper\ArrayAccess;

class Suggestion
{
    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var int
     */
    private $translationId = 0;

    /**
     * @var string
     */
    private $suggestion;

    /**
     * This method simply copies the data from the passed in array to our entities properties.
     *
     * @param array $data Data from database
     *
     * @return void
     */
    public function exchangeArray(array $data): void
    {
        $this->id            = ArrayAccess::getInt($data, 'id');
        $this->translationId = ArrayAccess::getInt($data, 'translationId');
        $this->suggestion    = ArrayAccess::getString($data, 'suggestion');
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'            => $this->id,
            'translationId' => $this->translationId,
            'suggestion'    => $this->suggestion,
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    public function getSuggestion(): string
    {
        return $this->suggestion;
    }
}
