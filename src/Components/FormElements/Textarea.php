<?php

namespace Cmsify\Cmsify\Components\FormElements;

class Textarea extends AbstractInput
{

    /**
     * @var string
     */
    protected $componentName = 'textarea-input';

    /**
     * Textarea constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->inputName = $name;
    }

    /**
     * @return array
     */
    public function build(): array
    {
        return $this->response();
    }
}
