<?php
namespace Application\Model;

use \Application\Helper\ArrayAccess;

class TranslationBase
{
    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var int
     */
    private $fileId = 0;

    /**
     * @var string
     */
    private $originSource;

    /**
     * This method simply copies the data from the passed in array to our entities properties.
     *
     * @param array $data Data from database
     *
     * @return void
     */
    public function exchangeArray(array $data): void
    {
        $this->id           = ArrayAccess::getInt($data, 'id');
        $this->fileId       = ArrayAccess::getInt($data, 'fileId');
        $this->originSource = ArrayAccess::getString($data, 'originSource');
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'fileId'       => $this->fileId,
            'originSource' => $this->originSource,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getFileId(): int
    {
        return $this->fileId;
    }

    public function setFileId(int $fileId): self
    {
        $this->fileId = $fileId;
        return $this;
    }

    public function getOriginSource(): ?string
    {
        return $this->originSource;
    }

    public function setOriginSource(?string $originSource): self
    {
        $this->originSource = $originSource;
        return $this;
    }
}