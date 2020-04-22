<?php

namespace Cmsify\Cmsify\Components\Relations;

use Closure;
use Cmsify\Cmsify\Components\ComponentSet;
use Cmsify\Cmsify\Components\FormElements\AbstractInput;
use Cmsify\Cmsify\Components\FormElements\Hidden;
use Cmsify\Cmsify\Components\FormElements\Relations;
use Illuminate\Support\Str;

class HasMany extends AbstractRelation
{

    /**
     * @var string
     */
    protected $componentName = 'has-many';
    /**
     * @var string
     */
    protected $relationName;

    /**
     * @var ComponentSet
     */
    private $componentSet;

    /**
     * @var Closure
     */
    private $closure;

    /**
     * HasMany constructor.
     * @param string $relationName
     */
    public function __construct(string $relationName)
    {
        $this->relationName = $relationName;
        $this->componentSet = app(ComponentSet::class);
        $this->components = collect([]);
    }

    /**
     * @param Closure $componentSetClosure
     * @return $this
     */
    public function components(Closure $componentSetClosure): self
    {
        $this->closure = $componentSetClosure;

        return $this;
    }

    /**
     * @return array
     */
    public function build(): array
    {
        $componentSetClosure = $this->closure;
        $componentSetClosure($this->componentSet);

        $components = $this->componentSet->getComponents();

        $items = [];
        $componentId = 0;
        foreach ($this->getRelation()->get() as $model) {
            $hiddenIdInput = (new Hidden('id'))
                ->form($this->formName)
                ->model($model);
            $this->buildInputNameAttributes($hiddenIdInput, $componentId);
            $hiddenIdInput = $hiddenIdInput->build();

            $hiddenDeleteInput = (new Hidden('delete'))
                ->form($this->formName)
                ->value(0);
            $this->buildInputNameAttributes($hiddenDeleteInput, $componentId);
            $hiddenDeleteInput = $hiddenDeleteInput->build();

            $builtComponents = [
                $hiddenIdInput,
                $hiddenDeleteInput
            ];
            $item = [];

            foreach ($components as $component) {
                $childComponent = clone $component;
                $this->buildInputNameAttributes($childComponent, $componentId);

                if (method_exists($childComponent, 'form')) {
                    /** @var AbstractInput $childComponent */
                    $childComponent->form($this->formName);
                }

                if (method_exists($childComponent, 'model')) {
                    /** @var AbstractInput $childComponent */
                    $childComponent->model($model);
                }

                $builtComponents[] = $childComponent->build();
            }
            $item['components'] = $builtComponents;
            $item['config'] = [
                'is_visible'  => false,
                'is_deleted'  => false,
                'entry_label' => $this->entryLabel,
            ];
            $items[] = $item;
            $componentId++;
        }

        $response = $this->response();
        $response['items'] = $items;

        return $response;
    }

    /**
     * @return array
     */
    protected function getTemplate(): array
    {
        $components = $this->componentSet->getComponents();

        $hiddenDeleteInput = (new Hidden('delete'))
            ->form($this->formName)
            ->value(0);
        $this->buildInputNameAttributes($hiddenDeleteInput, '_ID_');
        $hiddenDeleteInput = $hiddenDeleteInput->build();

        $builtComponents = [
            $hiddenDeleteInput
        ];
        foreach ($components as $component) {
            $childComponent = clone $component;
            $this->buildInputNameAttributes($childComponent, '_ID_');

            if (method_exists($childComponent, 'form')) {
                /** @var AbstractInput $childComponent */
                $childComponent->form($this->formName);
            }

            $builtComponents[] = $childComponent->build();
        }

        $template['components'] = $builtComponents;
        $template['config'] = [
            'is_visible'  => false,
            'is_deleted'  => false,
            'entry_label' => $this->entryLabel,
        ];

        return $template;
    }

    /**
     * @param AbstractInput $inputComponent
     * @param string $componentId
     * @return void
     */
    private function buildInputNameAttributes(AbstractInput $inputComponent, string $componentId): void
    {
        $inputComponent->addInputNameAttributeBefore($componentId);

        $inputComponent->addInputNameAttributeBefore($this->relationName);

        $inputComponent->getInputNameAttributes();
    }
}
