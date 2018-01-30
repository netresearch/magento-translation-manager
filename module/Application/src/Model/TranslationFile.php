<?php
namespace Application\Model;

use Application\Helper\ArrayAccess;

class TranslationFile
{
    /**
     * @var int
     */
    private $translationFileId = 0;

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
    public function exchangeArray(array $data)
    {
        $this->translationFileId = ArrayAccess::getInt($data, 'translation_file_id');
        $this->filename          = ArrayAccess::getString($data, 'filename');
        $this->sourcePath        = ArrayAccess::getString($data, 'source_path');
        $this->destinationPath   = ArrayAccess::getString($data, 'destination_path');
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'translation_file_id' => $this->translationFileId,
            'filename'            => $this->filename,
            'source_path'         => $this->sourcePath,
            'destination_path'    => $this->destinationPath,
        ];
    }

    /**
     * @return int
     */
    public function getTranslationFileId(): int
    {
        return $this->translationFileId;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getSourcePath(): string
    {
        return $this->sourcePath;
    }

    /**
     * @return string
     */
    public function getDestinationPath(): string
    {
        return $this->destinationPath;
    }
}
