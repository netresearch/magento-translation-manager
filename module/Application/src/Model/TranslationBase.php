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
    private $file;

    /**
     * @var string
     */
    private $originSource;

    /**
     * @var bool
     */
    private $notInUse = true;

    /**
     * @var string
     */
    private $screenPath;

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
        $this->file         = ArrayAccess::getString($data, 'file');
        $this->originSource = ArrayAccess::getString($data, 'originSource');
        $this->notInUse     = ArrayAccess::getBool($data, 'notInUse', true);
        $this->screenPath   = ArrayAccess::getString($data, 'screenPath');
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
            'file'         => $this->file,
            'originSource' => $this->originSource,
            'notInUse'     => (int) $this->notInUse,
            'screenPath'   => $this->screenPath,
        ];
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function getOriginSource(): string
    {
        return $this->originSource;
    }

    public function setOriginSource(string $originSource): self
    {
        $this->originSource = $originSource;
        return $this;
    }

    public function getNotInUse(): bool
    {
        return $this->notInUse;
    }

    public function getScreenPath(): ?string
    {
        return $this->screenPath;
    }

    public function setScreenPath(?string $screenPath): self
    {
        $this->screenPath = $screenPath;
        return $this;
    }
}