<?php
namespace Locale\Model;

use \Zend\InputFilter\InputFilter;
use \Zend\InputFilter\InputFilterAwareInterface;
use \Zend\InputFilter\InputFilterInterface;

use \Application\Helper\ArrayAccess;

class SupportedLocale implements InputFilterAwareInterface
{
    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var string
     */
    private $locale;

    private $inputFilter;

    /**
     * This method simply copies the data from the passed in array to our entities properties.
     *
     * @param array $data Data from database
     *
     * @return void
     */
    public function exchangeArray(array $data): void
    {
        $this->id     = ArrayAccess::getInt($data, 'id');
        $this->locale = ArrayAccess::getString($data, 'locale');
    }

    /**
     * Cast model to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'     => $this->id,
            'locale' => $this->locale,
        ];
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add([
                'name'     => 'id',
                'required' => true,
                'filters'  => [
                     [ 'name' => 'Int' ],
                ],
            ]);

            $inputFilter->add(array(
                'name'     => 'locale',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('Not used');
    }
}
