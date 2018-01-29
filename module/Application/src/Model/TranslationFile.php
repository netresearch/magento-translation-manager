<?php

namespace Application\Model;

class TranslationFile
{
    /**
     * @var int
     */
    private $translationFileId;

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
        $this->translationFileId = (!empty($data['translation_file_id'])) ? ((int) $data['translation_file_id']) : null;
        $this->filename          = (!empty($data['filename'])) ? $data['filename'] : null;
        $this->sourcePath        = (!empty($data['source_path'])) ? $data['source_path'] : null;
        $this->destinationPath   = (!empty($data['destination_path'])) ? $data['destination_path'] : null;
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray()
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
    public function getTranslationFileId() {
        return $this->translationFileId;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getSourcePath()
    {
        return $this->sourcePath;
    }

    /**
     * @return string
     */
    public function getDestinationPath()
    {
        return $this->destinationPath;
    }
}
