<?php
namespace Export\Form;

use \Zend\Form\Form;
use \Zend\Form\Element\Checkbox;
use \Zend\Form\Element\Select;
use \Zend\Form\Element\Button;
use \Zend\InputFilter\InputFilter;
// use \Zend\Validator\NotEmpty;
use \Application\ResultSet\Locale as ResultSet_Locale;
use \Application\ResultSet\TranslationFile as ResultSet_TranslationFile;

/**
 * Class representing the locale form.
 */
class ExportForm extends Form
{
    /**
     * Constructor.
     *
     * @param ResultSet_TranslationFile $files   List of files
     * @param ResultSet_Locale          $locales List of available locales
     */
    public function __construct(ResultSet_TranslationFile $files, ResultSet_Locale $locales)
    {
        // We want to ignore the name passed
        parent::__construct('export-form');

        $this->setAttribute('method', 'post');

        $this->addElements($files, $locales);
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     *
     * @param ResultSet_TranslationFile $files   List of files
     * @param ResultSet_Locale          $locales List of available locales
     *
     * @return void
     */
    private function addElements(ResultSet_TranslationFile $files, ResultSet_Locale $locales): void
    {
        // File selector
        $valueOptions = [];

        /** @var \Application\Model\TranslationFile $file */
        foreach ($files as $file) {
            $valueOptions[$file->getFilename()] = $file->getFilename();
        }

        $this->add([
            'name' => 'files',
            'type' => Select::class,
            'attributes' => [
                'id' => 'files',
                'class' => 'form-control',
                'multiple' => true,
                'size' => 5,
            ],
            'options' => [
                'label' => 'Translation file',
                'value_options' => $valueOptions,
            ],
        ]);

        // Locale selector
        $valueOptions = [];

        /** @var \Application\Model\Locale $locale */
        foreach ($locales as $locale) {
            $valueOptions[$locale->getLocale()] = $locale->getLocale();
        }

        $this->add([
            'name' => 'locales',
            'type' => Select::class,
            'attributes' => [
                'id' => 'locales',
                'class' => 'form-control',
                'multiple' => true,
                'size' => 5,
            ],
            'options' => [
                'label' => 'Language',
                'value_options' => $valueOptions,
            ],
        ]);

        // CSV checkbox
        // https://www.w3.org/TR/html401/interact/forms.html#h-17.12.1
        // - Disabled form elements are not submitted!
        $this->add([
            'name' => 'exportCsv',
            'type' => Checkbox::class,
            'attributes' => [
                'id' => 'exportCsv',
                'class' => 'form-check-input',
                'value' => 1,
                'disabled' => true,
            ],
            'options' => [
                'label' => 'CSV (Download)',
                'label_attributes' => [
                    'class' => 'form-check-label',
                ],
                'checked_value' => '1',
                'unchecked_value' => '',
            ],
        ]);

        // Submit button
        $this->add([
            'name' => 'submit',
            'type' => Button::class,
            'options' => [
                'label' => 'Start export',
            ],
            'attributes' => [
                'id' => 'submitbutton',
                'type' => 'submit',
                'class' => 'btn btn-info',
            ],
        ]);
    }

    /**
     * This method creates input filters (used for form filtering/validation).
     *
     * @return void
     */
    private function addInputFilter(): void
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // Add validation rules for the "files" field
        $inputFilter->add([
            'name' => 'files',
            'required' => true,
        ]);

        // Add validation rules for the "files" field
        $inputFilter->add([
            'name' => 'locales',
            'required' => true,
        ]);

        // Add validation rules for the "files" field
        $inputFilter->add([
            'name' => 'exportCsv',
            'required' => false,
//             'validators' => [
//                 [
//                     'name' => NotEmpty::class,
//                     'break_chain_on_failure' => true,
//                     'options' => array(
//                         'messages' => array(
//                             NotEmpty::IS_EMPTY => 'Please select the file type to export too',
//                         ),
//                     ),
//                 ],
//             ],
        ]);
    }
}
