<?php

namespace Cmsify\Cmsify\Components\FormElements;

use Cmsify\Cmsify\Components\Componentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

abstract class AbstractInput implements Componentable
{

    /**
     * @var string
     */
    protected $inputName;

    /**
     * @var string
     */
    protected $componentName;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var string
     */
    private $formName;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var array
     */
    private $inputNameAttributes = [];

    /**
     * @var string|int|float
     */
    private $value;

    /**
     * @return string
     */
    protected function getLabel(): string
    {
        return ucfirst(str_replace('_', ' ', $this->inputName));
    }

    /**
     * @param array $attributes
     * @return AbstractInput
     */
    public function attributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param string $name
     * @return AbstractInput
     */
    public function form(string $name): AbstractInput
    {
        $this->formName = $name;

        return $this;
    }

    /**
     * @param Model $model
     * @return $this
     */
    public function model(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function value($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string|null|int|float
     */
    public function getValue()
    {
        if ($this->value !== null) {
            return $this->value;
        }

        return $this->model ? $this->model->{$this->inputName} : null;
    }

    /**
     * @return string
     */
    public function getInputName(): string
    {
        return implode('.', $this->getInputNameAttributes());
    }

    /**
     * @return string
     */
    public function getElementId(): string
    {
        return implode('-', $this->getInputNameAttributes());
    }

    /**
     * @return array
     */
    public function getInputNameAttributes(): array
    {
        if (!count($this->inputNameAttributes)) {
            $this->inputNameAttributes = [
                $this->inputName
            ];
        }

        return $this->inputNameAttributes;
    }

    /**
     * @param string $attribute
     * @return AbstractInput
     */
    public function addInputNameAttributeBefore(string $attribute): AbstractInput
    {
        $this->inputNameAttributes = array_merge([$attribute], $this->getInputNameAttributes());

        return $this;
    }

    /**
     * @param string $attribute
     * @return AbstractInput
     */
    public function addInputNameAttributeAfter(string $attribute): AbstractInput
    {
        $this->inputNameAttributes = array_merge($this->getInputNameAttributes(), [$attribute]);

        return $this;
    }

    /**
     * @return array
     */
    protected function response(): array
    {
        return [
            'component_name'  => $this->componentName,
            'input_name'      => $this->inputName,
            'full_input_name' => $this->getInputName(),
            'element_id'      => $this->getElementId(),
            'label'           => $this->getLabel(),
            'attributes'      => $this->attributes,
            'form'            => $this->formName,
            'value'           => $this->getValue(),
            'error'           => null,
        ];
    }
}
