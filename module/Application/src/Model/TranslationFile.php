<?php
namespace Application\Model;

use \Application\Helper\ArrayAccess;

class TranslationFile
{
    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $sourcePath;

    /**
     * @var string
     */
    private $destinationPath;

    /**
     * This method simply copies the data from the passed in array to our entities properties.
     *
     * @param array $data Data from database
     *
     * @return void
     */
    public function exchangeArray(array $data): void
    {
        $this->id              = ArrayAccess::getInt($data, 'id');
        $this->filename        = ArrayAccess::getString($data, 'filename');
        $this->sourcePath      = ArrayAccess::getString($data, 'sourcePath');
        $this->destinationPath = ArrayAccess::getString($data, 'destinationPath');
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'              => $this->id,
            'filename'        => $this->filename,
            'sourcePath'      => $this->sourcePath,
            'destinationPath' => $this->destinationPath,
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
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return self
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return string
     */
    public function getSourcePath(): string
    {
        return $this->sourcePath;
    }

    /**
     * @return self
     */
    public function setSourcePath(string $sourcePath): self
    {
        $this->sourcePath = $sourcePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestinationPath(): string
    {
        return $this->destinationPath;
    }

    /**
     * @return self
     */
    public function setDestinationPath(string $destinationPath): self
    {
        $this->destinationPath = $destinationPath;
        return $this;
    }
}
