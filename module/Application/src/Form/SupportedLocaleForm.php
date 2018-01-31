<?php
namespace Application\Form;

use \Zend\Form\Form;

/**
 * Class representing the supported locale form.
 */
class SupportedLocaleForm extends Form
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
            'type' => 'Text',
            'options' => array(
                'label' => 'Locale',
            ),
            'attributes' => array(
                'maxlength' => 5,
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Submit',
                'id'    => 'submitbutton',
            ),
        ));
    }
}
