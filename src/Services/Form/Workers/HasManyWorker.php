<?php

namespace Cmsify\Cmsify\Services\Form\Workers;

use Cmsify\Cmsify\Registries\FormWorkerRegistry;
use Cmsify\Cmsify\Services\Form\FormHandler;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class HasManyWorker implements FormWorkable
{

    /**
     * @var Model
     */
    private $model;

    /**
     * @var array
     */
    private $hasManyData;

    /**
     * @var FormWorkerRegistry
     */
    private $workerRegistry;

    /**
     * HasManyWorker constructor.
     * @param Model $model
     * @param array $hasManyData
     * @param FormWorkerRegistry $workerRegistry
     */
    public function __construct(Model $model, array $hasManyData, FormWorkerRegistry $workerRegistry)
    {
        $this->model = $model;
        $this->hasManyData = $hasManyData;
        $this->workerRegistry = $workerRegistry;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function prepareAndSave(): void
    {
        foreach ($this->hasManyData['entries'] as $components) {
            $components = collect($components);
            $model = $this->getModel($components);
            $action = $model instanceof Model ? 'update' : 'create';

            $deleteEntry = $components->filter(static function (array $component) {
                return $component['input_name'] === 'delete' && $component['value'] === 1;
            })->count();

            if ($deleteEntry && $action === 'update') {
                $model->delete();
            }

            if($deleteEntry) {
                continue;
            }

            $components = $components->filter(static function (array $component) {
                return !in_array($component['input_name'], [
                    'id',
                    'delete',
                ]);
            });

            FormHandler::prepareAndSave($model, $components->toArray(), $this->workerRegistry, $action);
        }
    }

    /**
     * @param array $hasManyData
     * @return array
     */
    public static function prepareForValidation(array $hasManyData): array
    {
        $preparedEntries = [];

        foreach ($hasManyData['entries'] as $entry) {
            $deletedEntry = false;
            $preparedEntry = [];
            foreach ($entry as $entryItem) {
                if ($entryItem['input_name'] === 'delete' && $entryItem['value'] === 1) {
                    $deletedEntry = true;
                }
                $preparedEntry[$entryItem['input_name']] = $entryItem['value'];
            }

            if($deletedEntry) {
                continue;
            }
            $preparedEntries[] = $preparedEntry;
        }

        return [
            $hasManyData['relation'] => $preparedEntries
        ];
    }

    /**
     * @param Collection $components
     * @return Model|HasMany
     */
    private function getModel(Collection $components)
    {
        $idComponent = $components->where('input_name', 'id')->first();
        if ($idComponent) {
            return $this->getRelation()->where('id', $idComponent['value'])->first();
        }

        return $this->getRelation();
    }

    /**
     * @return HasMany
     */
    public function getRelation(): HasMany
    {
        return $this->model->{$this->hasManyData['relation']}();
    }
}
