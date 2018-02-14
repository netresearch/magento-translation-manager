<?php
namespace Application\Form;

use \Zend\Form\Form;
use \Zend\Form\Element\Text;
use \Zend\Form\Element\Button;

/**
 * Class representing the supported locale form.
 */
class LocaleForm extends Form
{
    public function __construct($name = null)
    {
        // We want to ignore the name passed
        parent::__construct('supportedLocale');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'locale',
            'type' => Text::class,
            'options' => array(
                'label' => 'Locale',
            ),
            'attributes' => array(
                'maxlength' => 5,
                'class'     => 'form-control',
            ),
        ));

        // Submit button
        $this->add([
            'name' => 'submit',
            'type' => Button::class,
            'options' => [
                'label' => 'Save',
            ],
            'attributes' => [
                'id' => 'submitbutton',
                'type' => 'submit',
                'class' => 'btn btn-info',
            ],
        ]);
    }
}
