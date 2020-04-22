<?php

namespace Cmsify\Cmsify\Services\Form\Workers;

use Cmsify\Cmsify\Registries\FormWorkerRegistry;
use Illuminate\Database\Eloquent\Model;

interface FormWorkable {

    /**
     * FormWorkable constructor.
     * @param Model $model
     * @param array $componentData
     * @param FormWorkerRegistry $workerRegistry
     */
    public function __construct(Model $model, array $componentData, FormWorkerRegistry $workerRegistry);

    /**
     * @return void
     */
    public function prepareAndSave(): void;

    /**
     * @param array $relationData
     * @return array
     */
    public static function prepareForValidation(array $relationData): array;
}
