<?php
namespace Import\Form;

use \Zend\Form\Form;
use \Zend\Form\Element\File;
use \Zend\Form\Element\Select;
use \Zend\Form\Element\Submit;
use \Zend\InputFilter\InputFilter;
use \Zend\InputFilter\FileInput;
use \Application\Model\Locale;
use \Application\ResultSet\Locale as ResultSet_Locale;

/**
 * Class representing the locale form.
 */
class ImportForm extends Form
{
    /**
     * Constructor.
     *
     * @param ResultSet_Locale $locales List of available locales
     */
    public function __construct(ResultSet_Locale $locales)
    {
        // We want to ignore the name passed
        parent::__construct('import-form');

        $this->setAttribute('method', 'post')
            ->setAttribute('enctype', 'multipart/form-data');

        $this->addElements($locales);
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     *
     * @param ResultSet_Locale $locales List of available locales
     *
     * @return void
     */
    private function addElements(ResultSet_Locale $locales): void
    {
        // File selector
        $this->add([
            'name' => 'files',
            'type' => File::class,
            'attributes' => [
                'id'       => 'filebutton',
                'class'    => 'form-control-file',
                'multiple' => true,
            ],
            'options' => [
                'label' => 'Select File(s)',
                'label_attributes' => [
                    'class' => ''
                ],
            ],
        ]);

        // Locale selector
        $valueOptions = [];

        /** @var Locale $locale */
        foreach ($locales as $locale) {
            $valueOptions[$locale->getLocale()] = $locale->getLocale();
        }

        $this->add([
            'name' => 'locale',
            'type' => Select::class,
            'attributes' => [
                'id'    => 'locale',
                'class' => 'form-control',
            ],
            'options' => [
                'label'         => 'Import as locale',
                'empty_option'  => '-- Please select --',
                'value_options' => $valueOptions,
            ],
        ]);

        // Submit button
        $this->add([
            'name' => 'submit',
            'type' => Submit::class,
            'attributes' => [
                'value' => 'Start import',
                'id'    => 'submitbutton',
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

        // Add validation rules for the "file" field
        $inputFilter->add([
            'type'     => FileInput::class,
            'name'     => 'files',
            'required' => true,
            'validators' => [
                [
                    'name' => 'FileUploadFile'
                ],
                [
                    'name' => 'FileExtension',
                    'options' => [
                        'extension'  => ['csv']
                    ]
                ],
            ],
            'filters'  => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target' => './data/upload',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => false
                    ]
                ]
            ],
        ]);
    }
}
