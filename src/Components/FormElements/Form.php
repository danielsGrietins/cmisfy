<?php

namespace Cmsify\Cmsify\Components\FormElements;

use Cmsify\Cmsify\Components\Componentable;
use Cmsify\Cmsify\Services\Form\FormBuilder;

class Form implements Componentable
{

    /**
     * @var FormBuilder
     */
    private $formBuilder;

    /**
     * Form constructor.
     * @param FormBuilder $formBuilder
     */
    public function __construct(FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
    }

    /**
     * @return array
     */
    public function build(): array
    {
        return [
            'component_name'    => 'custom-form',
            'name'              => $this->formBuilder->getName(),
            'url'               => $this->formBuilder->getUrl(),
            'method'            => $this->formBuilder->getMethod(),
            'has_submit_button' => $this->formBuilder->hasSubmitButton(),
            'components'        => $this->formBuilder->getComponents()
        ];
    }
}
