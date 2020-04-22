<?php

namespace Cmsify\Cmsify\Registries;

use Cmsify\Cmsify\Services\Form\Workers\HasManyWorker;

class FormWorkerRegistry
{

    /**
     * @return array
     */
    public function getList(): array
    {
        return [
            'has-many' => HasManyWorker::class,
        ];
    }

    /**
     * @param string $name
     * @return string
     */
    public function getClass(string $name): string
    {
        return $this->getList()[$name];
    }

    /**
     * @return array
     */
    public function getNames(): array
    {
        return array_keys($this->getList());
    }

    /**
     * @param string $componentName
     * @return bool
     */
    public function exist(string $componentName): bool
    {
        return in_array($componentName, $this->getNames(), true);
    }
}
