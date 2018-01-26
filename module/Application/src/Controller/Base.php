<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class Base extends AbstractActionController {
    const DEFAULT_LOCALE = 'de_DE';

    const MESSAGE_INFO = 'info';
    const MESSAGE_WARN = 'warning';
    const MESSAGE_ERROR = 'danger';
    const MESSAGE_SUCCESS = 'success';

    /**
     * @var array - system messages
     */
    protected $_messages = array( /* type => array (message) */ );

    /**
     * @var $_translationBaseTable \Application\Resource\TranslationBase
     */
    protected $_translationBaseTable = null;
    /**
     * @var $_translationTable \Application\Resource\Translation
     */
    protected $_translationTable = null;
    /**
     * @var $_translationTable \Application\Resource\TranslationFile
     */
    protected $_translationFileTable = null;
    /**
     * @var $_suggestionTable \Application\Resource\Suggestion
     */
    protected $_suggestionTable = null;

    /**
     * @var array - supported Locales
     */
    protected $_supportedLocale = null;

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
     * add message to system message queue
     *
     * @param $message - message to note
     * @param string $level - message leven eg.g error or info
     */
    protected function addMessage($message, $level = self::MESSAGE_INFO)
    {
        $this->_messages[$level][] = $message;
    }
}
