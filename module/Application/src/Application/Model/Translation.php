<?php

namespace Application\Model;

class Translation extends Base {

    /* @var int $_id */
    protected $_id = null;
    /* @var int $_baseId */
    protected $_baseId = null;
    /* @var string $_locale */
    protected $_locale = null;
    /* @var string $_currentTranslation */
    protected $_currentTranslation = null;
    /* @var string $_suggestedTranslation */
    protected $_suggestedTranslation = null;
    /* @var string $_unclearTranslation */
    protected $_unclearTranslation = null;

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    public function getBaseId() {
        return $this->_baseId;
    }

    public function setBaseId($baseId) {
        $this->_baseId = (int)$baseId;
        return $this;
    }

    public function getLocale() {
        return $this->_locale;
    }

    public function setLocale($locale) {
        $this->_locale = $locale;
        return $this;
    }

    public function getCurrentTranslation() {
        return $this->_currentTranslation;
    }

    public function setCurrentTranslation($currentTranslation) {
        $this->_currentTranslation = $currentTranslation;
        return $this;
    }

    public function getSuggestedTranslation() {
        return $this->_suggestedTranslation;
    }

    public function setSuggestedTranslation($suggestedTranslation) {
        $this->_suggestedTranslation = $suggestedTranslation;
        return $this;
    }

    public function getUnclearTranslation() {
        return $this->_suggestedTranslation;
    }

    public function setUnclearTranslation($unclearTranslation) {
        $this->_unclearTranslation = (bool)$unclearTranslation;
        return $this;
    }
}