<?php


namespace App\Helper;


class PropertyObject
{
    private $reflection;
    private $object;

    public function __construct($object)
    {
        $this->object = $object;
        $this->reflection = new \ReflectionObject($this->object);

    }

    public function getPropertyValue(string $variableName)
    {
        $property = $this->reflection->getProperty($variableName);
        $property->setAccessible(true);
        $value = $property->getValue($this->object);

        return $value;
    }

    public function setPropertyValue(string $variableName, $value): void
    {
        $property = $this->reflection->getProperty($variableName);
        $property->setAccessible(true);
        $value = $property->getValue($this->object, $value);

    }


}