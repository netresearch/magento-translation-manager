<?php
namespace Application\View\Helper;

use \Zend\View\Helper\AbstractHelper;
use \Application\Model\Locale;

/**
 * Helper for rendering a flag image based on locale.
 */
class Flag extends AbstractHelper
{
    /**
     * Helper entry point.
     *
     * @param Locale|string $locale Locale string or object
     *
     * @return string
     */
    public function __invoke($locale): string
    {
        if ($locale instanceof Locale) {
            $locale = $locale->getLocale();
        }

        // Extract country code
        $countryCode = strtolower(substr($locale, 3, 2));

        return sprintf(
            '<img src="%s.png" title="%s" alt="">',
            $this->view->basePath('img/flags/' . $countryCode),
            $this->view->escapeHtml($this->view->translate($locale))
        );
    }
}
