<?php

namespace Cmsify\Cmsify\Services\Form;

use Closure;
use Cmsify\Cmsify\Components\ComponentSet;
use Cmsify\Cmsify\Components\FormElements\AbstractInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FormBuilder
{

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method;

    /**
     * @var bool
     */
    private $hasSubmitButton;

    /**
     * @var ComponentSet
     */
    private $componentSet;

    /**
     * @var Collection
     */
    private $components;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var string
     */
    private $request;

    /**
     * Form constructor.
     * @param string $name
     * @param string $url
     * @param string $method
     * @param bool $hasSubmitButton
     */
    public function __construct(string $name, string $url, string $method = 'POST', bool $hasSubmitButton = true)
    {
        $this->name = $name;
        $this->url = $url;
        $this->method = $method;
        $this->hasSubmitButton = $hasSubmitButton;
        $this->componentSet = app(ComponentSet::class);
        $this->components = collect([]);
    }

    /**
     * @param Closure $componentSetClosure
     * @return $this
     */
    public function components(Closure $componentSetClosure): self
    {
        $componentSetClosure($this->componentSet);

        $components = $this->componentSet->getComponents();

        foreach ($components as $component) {
            if (method_exists($component, 'form')) {
                /** @var AbstractInput $component */
                $component->form($this->name);
            }

            if (method_exists($component, 'model')) {
                /** @var AbstractInput $component */
                $component->model($this->model);
            }

            $this->components->push($component->build());
        }

        return $this;
    }

    /**
     * @param string
     * @return $this
     */
    public function request(string $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return string
     */
    public function getRequest(): string
    {
        if (!$this->request) {
            return Request::class;
        }

        return $this->request;
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
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return bool
     */
    public function hasSubmitButton(): bool
    {
        return $this->hasSubmitButton;
    }

    /**
     * @return Collection
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }
}
