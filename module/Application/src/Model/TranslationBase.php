<?php

namespace Application\Model;

class TranslationBase
{
    /**
     * @var int
     */
    private $baseId;

    /**
     * @var int
     */
    private $translationFileId;

    /**
     * @var string
     */
    private $translationFile;

    /**
     * @var string
     */
    private $originSource;

    /**
     * @var boolean
     */
    private $notInUse;

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
        $this->baseId            = (!empty($data['base_id'])) ? ((int) $data['base_id']) : null;
        $this->translationFileId = (!empty($data['translation_file_id'])) ? ((int) $data['translation_file_id']) : null;
        $this->translationFile   = (!empty($data['translation_file'])) ? $data['translation_file'] : null;
        $this->originSource      = (!empty($data['origin_source'])) ? $data['origin_source'] : null;
        $this->notInUse          = (!empty($data['not_in_use'])) ? ((bool) $data['not_in_use']) : null;
        $this->screenPath        = (!empty($data['screen_path'])) ? $data['screen_path'] : null;
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray()
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

    public function getBaseId()
    {
        return $this->baseId;
    }

    public function getTranslationFileId()
    {
        return $this->translationFileId;
    }

    public function getTranslationFile()
    {
        return $this->translationFile;
    }

    public function getOriginSource()
    {
        return $this->originSource;
    }

    public function getNotInUse()
    {
        return $this->notInUse;
    }

    public function getScreenPath()
    {
        return $this->screenPath;
    }
}