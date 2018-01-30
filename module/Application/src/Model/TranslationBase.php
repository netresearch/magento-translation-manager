<?php
namespace Application\Model;

use Application\Helper\ArrayAccess;

class TranslationBase
{
    /**
     * @var int
     */
    private $baseId = 0;

    /**
     * @var int
     */
    private $translationFileId = 0;

    /**
     * @var string
     */
    private $translationFile;

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
        $this->baseId            = ArrayAccess::getInt($data, 'base_id');
        $this->translationFileId = ArrayAccess::getInt($data, 'translation_file_id');
        $this->translationFile   = ArrayAccess::getString($data, 'translation_file');
        $this->originSource      = ArrayAccess::getString($data, 'origin_source');
        $this->notInUse          = ArrayAccess::getBool($data, 'not_in_use', true);
        $this->screenPath        = ArrayAccess::getString($data, 'screen_path');
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'base_id'             => $this->baseId,
            'translation_file_id' => $this->translationFileId,
            'translation_file'    => $this->translationFile,
            'origin_source'       => $this->originSource,
            'not_in_use'          => (int) $this->notInUse,
            'screen_path'         => $this->screenPath,
        ];
    }

    public function getBaseId(): int
    {
        return $this->baseId;
    }

    public function getTranslationFileId(): int
    {
        return $this->translationFileId;
    }

    public function getTranslationFile(): string
    {
        return $this->translationFile;
    }

    public function getOriginSource(): string
    {
        return $this->originSource;
    }

    public function getNotInUse(): bool
    {
        return $this->notInUse;
    }

    public function getScreenPath(): string
    {
        return (string) $this->screenPath;
    }
}