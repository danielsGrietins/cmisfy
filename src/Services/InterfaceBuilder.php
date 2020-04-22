<?php

namespace Cmsify\Cmsify\Services;

use Cmsify\Cmsify\Components\ComponentSet;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class InterfaceBuilder
{

    /**
     * @var ComponentSet
     */
    private $componentSet;

    /**
     * @var Collection
     */
    private $components;

    /**
     * InterfaceBuilder constructor.
     */
    public function __construct()
    {
        $this->componentSet = app(ComponentSet::class);
        $this->components = collect([]);
    }

    /**
     * @param Closure $closure
     * @return InterfaceBuilder
     */
    public function create(Closure $closure): InterfaceBuilder
    {
        $closure($this->componentSet);
        $components = $this->componentSet->getComponents();

        foreach ($components as $component) {
            $this->components->push($component->build());
        }

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function response(): JsonResponse
    {
        return response()->json([
            'components' => $this->components
        ]);
    }
}
