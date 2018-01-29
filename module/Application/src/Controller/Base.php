<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class Base extends AbstractActionController
{
    const DEFAULT_LOCALE = 'de_DE';

    const MESSAGE_INFO = 'info';
    const MESSAGE_WARN = 'warning';
    const MESSAGE_ERROR = 'danger';
    const MESSAGE_SUCCESS = 'success';

    /**
     * @var array - system messages
     */
    protected $_messages = array();

    /**
     * @var \Application\Resource\TranslationBase
     */
    protected $_translationBaseTable;

    /**
     * @var \Application\Resource\Translation
     */
    protected $_translationTable;

    /**
     * @var \Application\Resource\TranslationFile
     */
    protected $_translationFileTable;

    /**
     * @var \Application\Resource\Suggestion
     */
    protected $_suggestionTable;

    /**
     * @var array - supported Locales
     */
    protected $_supportedLocale;

    public function __construct(
        \Application\Resource\SupportedLocale $supportedLocale,
        \Application\Resource\Translation     $translation,
        \Application\Resource\TranslationBase $translationBaseTable,
        \Application\Resource\TranslationFile $translationFileTable,
        \Application\Resource\Suggestion      $suggestionTable
    ) {
        $this->_supportedLocale      = $supportedLocale;
        $this->_translationTable     = $translation;
        $this->_translationBaseTable = $translationBaseTable;
        $this->_translationFileTable = $translationFileTable;
        $this->_suggestionTable      = $suggestionTable;
    }

    /**
     * Add message to system message queue.
     *
     * @param string $message Message to note
     * @param string $level   Message level eg.g error or info
     */
    protected function addMessage($message, $level = self::MESSAGE_INFO)
    {
        $this->_messages[$level][] = $message;
    }
}
