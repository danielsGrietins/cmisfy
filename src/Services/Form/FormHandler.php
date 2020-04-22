<?php

namespace Cmsify\Cmsify\Services\Form;


use App\Http\Requests\ArticleRequest;
use Cmsify\Cmsify\Registries\FormWorkerRegistry;
use Cmsify\Cmsify\Services\Form\Workers\FormWorkable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormHandler
{

    /**
     * @var FormBuilder
     */
    private $formBuilder;

    /**
     * @var FormWorkerRegistry
     */
    private $workerRegistry;

    public function __construct(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
        $this->workerRegistry = app(FormWorkerRegistry::class);
    }

    /**
     * @return FormRequest|Request
     */
    public function validate()
    {
        return app($this->formBuilder->getRequest());
    }


    /**
     * @param Request $request
     */
    public function update($request): void
    {
        $model = $this->formBuilder->getModel();
        $components = $request->all();
        $preparedDataForValidation = $this->prepareDataForValidation($components);
        /** @var ArticleRequest $request */

        $request->request->add($preparedDataForValidation);
        app(ArticleRequest::class);
        self::prepareAndSave($model, $components, $this->workerRegistry);
    }

    /**
     * @param Model $model
     * @param array $data
     * @param FormWorkerRegistry $workerRegistry
     * @param string $action
     */
    public static function prepareAndSave($model, array $data, FormWorkerRegistry $workerRegistry, string $action = 'update'): void
    {
        $preparedDataForSaving = [];
        foreach ($data as $item) {
            $componentName = $item['name'];

            if ($workerRegistry->exist($componentName)) {
                $workerName = $workerRegistry->getClass($componentName);

                /** @var FormWorkable $worker */
                $worker = new $workerName($model, $item, $workerRegistry);
                $worker->prepareAndSave();

                continue;
            }

            $preparedDataForSaving[$item['input_name']] = $item['value'];
        }

        if ($model->exists()) {
            $model->{$action}($preparedDataForSaving);

            return;
        }

        $model->{$action}($preparedDataForSaving);
    }

    /**
     * @param array $data
     * @return array
     */
    private function prepareDataForValidation(array $data): array
    {
        $prepared = [];
        foreach ($data as $item) {
            if ($this->workerRegistry->exist($item['name'])) {
                $workerName = $this->workerRegistry->getClass($item['name']);

                /** @var FormWorkable $worker */
                $preparedWorkableData = $workerName::prepareForValidation($item);

                $prepared = array_merge($prepared, $preparedWorkableData);

                continue;
            }

            $prepared[$item['input_name']] = $item['value'];
        }

        return $prepared;
    }

}
