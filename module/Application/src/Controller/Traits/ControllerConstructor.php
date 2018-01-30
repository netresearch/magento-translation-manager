<?php
namespace Application\Controller\Traits;

use \Application\Resource\SupportedLocale;
use \Application\Resource\Translation;
use \Application\Resource\TranslationBase;
use \Application\Resource\TranslationFile;
use \Application\Resource\Suggestion;

trait ControllerConstructor
{
    /**
     * @var SupportedLocale
     */
    private $_supportedLocale;

    /**
     * @var Translation
     */
    private $_translationTable;

    /**
     * @var TranslationBase
     */
    private $_translationBaseTable;

    /**
     * @var TranslationFile
     */
    private $_translationFileTable;

    /**
     * @var Suggestion
     */
    private $_suggestionTable;

    /**
     * Constructor.
     *
     * @param SupportedLocale $supportedLocale
     * @param Translation     $translation
     * @param TranslationBase $translationBaseTable
     * @param TranslationFile $translationFileTable
     * @param Suggestion      $suggestionTable
     */
    public function __construct(
        SupportedLocale $supportedLocale,
        Translation     $translation,
        TranslationBase $translationBaseTable,
        TranslationFile $translationFileTable,
        Suggestion      $suggestionTable
    ) {
        $this->_supportedLocale      = $supportedLocale;
        $this->_translationTable     = $translation;
        $this->_translationBaseTable = $translationBaseTable;
        $this->_translationFileTable = $translationFileTable;
        $this->_suggestionTable      = $suggestionTable;
    }
}
