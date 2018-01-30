<?php

namespace Application\Model;

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
    public function exchangeArray(array $data)
    {
        $this->baseId            = (int) (!empty($data['base_id'])) ? $data['base_id'] : 0;
        $this->translationFileId = (int) (!empty($data['translation_file_id'])) ? $data['translation_file_id'] : 0;
        $this->translationFile   = (string) (!empty($data['translation_file'])) ? $data['translation_file'] : null;
        $this->originSource      = (string) (!empty($data['origin_source'])) ? $data['origin_source'] : null;
        $this->notInUse          = (bool) (!empty($data['not_in_use'])) ? $data['not_in_use'] : true;
        $this->screenPath        = (string) (!empty($data['screen_path'])) ? $data['screen_path'] : null;
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