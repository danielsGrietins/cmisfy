<?php

namespace Cmsify\Cmsify\Components\Relations;

use Cmsify\Cmsify\Components\Componentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

abstract class AbstractRelation implements Componentable
{

    /**
     * @var string
     */
    protected $componentName;

    /**
     * @var string
     */
    protected $relationName;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $formName;

    /**
     * @var Collection
     */
    protected $components;

    /**
     * @var string
     */
    protected $entryLabel;

    /**
     * @return string
     */
    protected function getLabel(): string
    {
        return ucfirst(str_replace('_', ' ', $this->relationName));
    }

    /**
     * @param array $attributes
     * @return AbstractRelation
     */
    public function attributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param string $name
     * @return AbstractRelation
     */
    public function form(string $name): AbstractRelation
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
     * @return Relation
     */
    protected function getRelation(): Relation
    {
        return $this->model->{$this->relationName}();
    }

    /**
     * @param string $inputFieldName
     * @return $this
     */
    public function entryLabel(string $inputFieldName): self
    {
        $this->entryLabel = $inputFieldName;

        return $this;
    }

    /**
     * @return array
     */
    protected function response(): array
    {
        return [
            'component_name' => $this->componentName,
            'attributes'     => $this->attributes,
            'relation_name'  => $this->relationName,
            'form'           => $this->formName,
            'name'           => $this->getLabel(),
            'template'       => $this->getTemplate(),
        ];
    }
}
