<?php
namespace DebugExport\Form;

use \Zend\Form\Element\Checkbox;
use \Application\ResultSet\Locale as ResultSet_Locale;
use \Application\ResultSet\TranslationFile as ResultSet_TranslationFile;
use \Export\Form\ExportForm;

/**
 * Class representing the locale form.
 */
class DebugExportForm extends ExportForm
{
    /**
     * Constructor.
     *
     * @param ResultSet_TranslationFile $files   List of files
     * @param ResultSet_Locale          $locales List of available locales
     */
    public function __construct(ResultSet_TranslationFile $files, ResultSet_Locale $locales)
    {
        parent::__construct($files, $locales);

        $this->addElements();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     *
     * @return void
     */
    private function addElements(): void
    {
        // Add additional form fields
        $this->add([
            'name' => 'masterFile',
            'type' => Checkbox::class,
            'attributes' => [
                'id' => 'masterFile',
                'class' => 'form-check-input',
            ],
            'options' => [
                'label' => 'Create master export file',
                'label_attributes' => [
                    'class' => 'form-check-label',
                ],
            ],
        ]);

        $this->add([
            'name' => 'debugTranslations',
            'type' => Checkbox::class,
            'attributes' => [
                'id' => 'debugTranslations',
                'class' => 'form-check-input',
            ],
            'options' => [
                'label' => 'Create debug translations',
                'label_attributes' => [
                    'class' => 'form-check-label',
                ],
            ],
        ]);
    }
}
