<?php
namespace Application\Model;

use \Application\Helper\ArrayAccess;

class Translation
{
    /**
     * @var int
     */
    private $id = 0;

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
    private $translation;

    /**
     * @var bool
     */
    private $unclear = true;

    /**
     * This method simply copies the data from the passed in array to our entities properties.
     *
     * @param array $data Data from database
     *
     * @return void
     */
    public function exchangeArray(array $data): void
    {
        $this->id          = ArrayAccess::getInt($data, 'id');
        $this->baseId      = ArrayAccess::getInt($data, 'baseId');
        $this->locale      = ArrayAccess::getString($data, 'locale');
        $this->translation = ArrayAccess::getString($data, 'translation');
        $this->unclear     = ArrayAccess::getBool($data, 'unclear', true);
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'baseId'      => $this->baseId,
            'locale'      => $this->locale,
            'translation' => $this->translation,
            'unclear'     => (int) $this->unclear,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBaseId(): int
    {
        return $this->baseId;
    }

    public function setBaseId(int $baseId): self
    {
        $this->baseId = $baseId;
        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function getTranslation(): string
    {
        return $this->translation;
    }

    public function setTranslation(string $translation): self
    {
        $this->translation = $translation;
        return $this;
    }

    public function getUnclear(): bool
    {
        return $this->unclear;
    }

    public function setUnclear(bool $unclear): self
    {
        $this->unclear = $unclear;
        return $this;
    }
}
