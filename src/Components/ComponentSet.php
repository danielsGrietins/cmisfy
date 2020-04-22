<?php

namespace Cmsify\Cmsify\Components;

use Cmsify\Cmsify\Components\FormElements\Form;
use Cmsify\Cmsify\Components\FormElements\Hidden;
use Cmsify\Cmsify\Components\FormElements\Text;
use Cmsify\Cmsify\Components\FormElements\Textarea;
use Cmsify\Cmsify\Components\Relations\HasMany;
use Cmsify\Cmsify\Components\Table\Table;
use Cmsify\Cmsify\Registries\ComponentRegistry;
use Cmsify\Cmsify\Services\Form\FormBuilder;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class ComponentSet
 * @package Cmsify\Cmsify
 *
 * Custom
 * @method Form form(FormBuilder $formBuilder)
 *
 * Input fields
 * @method Text text(string $name)
 * @method Hidden hidden(string $name)
 * @method Textarea textarea(string $name)
 *
 * Relations
 * @method HasMany hasMany(string $relationName)
 *
 * Table
 * @method Table table(\Closure $columnSet)
 */
class ComponentSet
{

    /**
     * @var ComponentRegistry
     */
    private $componentRegistry;

    /**
     * @var Collection
     */
    private $components;

    /**
     * ComponentSet constructor.
     */
    public function __construct()
    {
        $this->componentRegistry = app(ComponentRegistry::class);
        $this->components = new Collection;
    }

    /**
     * @return Collection
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    /**
     * @param $componentName
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call(string $componentName, array $arguments)
    {
        if (!$this->componentRegistry->exist($componentName)) {
            throw new Exception('Component with name "' . $componentName . '" does not exist');
        }
        $componentClass = Arr::get($this->componentRegistry->getList(), $componentName);
        $component = new $componentClass(...$arguments);

        $this->getComponents()->push($component);

        return $component;
    }
}
