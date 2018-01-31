<?php
namespace Application\Controller\Traits;

use \Application\Model\SupportedLocaleTable;
use \Application\Model\TranslationTable;
use \Application\Model\TranslationBaseTable;
use \Application\Model\TranslationFileTable;
use \Application\Model\SuggestionTable;

trait ControllerConstructor
{
    /**
     * @var SupportedLocaleTable
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
     * @param SupportedLocaleTable $supportedLocale
     * @param TranslationTable     $translation
     * @param TranslationBaseTable $translationBaseTable
     * @param TranslationFileTable $translationFileTable
     * @param SuggestionTable      $suggestionTable
     */
    public function __construct(
        SupportedLocaleTable $supportedLocale,
        TranslationTable     $translation,
        TranslationBaseTable $translationBaseTable,
        TranslationFileTable $translationFileTable,
        SuggestionTable      $suggestionTable
    ) {
        $this->_supportedLocale      = $supportedLocale;
        $this->_translationTable     = $translation;
        $this->_translationBaseTable = $translationBaseTable;
        $this->_translationFileTable = $translationFileTable;
        $this->_suggestionTable      = $suggestionTable;
    }
}
