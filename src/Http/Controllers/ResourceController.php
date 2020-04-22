<?php

namespace Cmsify\Cmsify\Http\Controllers;

use Cmsify\Cmsify\Registries\ComponentRegistry;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class ResourceController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var ComponentRegistry
     */
    private $componentRegistry;

    public function __construct(ComponentRegistry $componentRegistry)
    {
        $this->componentRegistry = $componentRegistry;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {

        return response()->json($this->getRoutes());
    }

    /**
     * @return array
     */
    private function getRoutes(): array
    {
        return [
            [
                'url'  => 'articles',
                'name' => 'Articles'
            ]
        ];
    }
}
