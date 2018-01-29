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
     * @var \Application\Resource\TranslationBase
     */
    private $_translationBaseTable;

    /**
     * @var \Application\Resource\Translation
     */
    private $_translationTable;

    /**
     * @var \Application\Resource\TranslationFile
     */
    private $_translationFileTable;

    /**
     * @var \Application\Resource\Suggestion
     */
    private $_suggestionTable;

    /**
     * @var array - supported Locales
     */
    private $_supportedLocale;

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
