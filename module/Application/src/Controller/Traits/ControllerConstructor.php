<?php
namespace Application\Controller\Traits;

use \Application\Model\LocaleTable;
use \Application\Model\TranslationTable;
use \Application\Model\TranslationBaseTable;
use \Application\Model\TranslationFileTable;
use \Application\Model\SuggestionTable;

trait ControllerConstructor
{
    /**
     * @var LocaleTable
     */
    private $_supportedLocale;

    /**
     * @var TranslationTable
     */
    private $_translationTable;

    /**
     * @var TranslationBaseTable
     */
    private $_translationBaseTable;

    /**
     * @var TranslationFileTable
     */
    private $_translationFileTable;

    /**
     * @var SuggestionTable
     */
    private $_suggestionTable;

    /**
     * Constructor.
     *
     * @param LocaleTable          $supportedLocale
     * @param TranslationTable     $translation
     * @param TranslationBaseTable $translationBaseTable
     * @param TranslationFileTable $translationFileTable
     * @param SuggestionTable      $suggestionTable
     */
    public function __construct(
        LocaleTable          $supportedLocale,
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
