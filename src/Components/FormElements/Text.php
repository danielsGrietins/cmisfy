<?php

namespace Cmsify\Cmsify\Components\FormElements;

class Text extends AbstractInput
{

    /**
     * @var string
     */
    protected $componentName = 'text-input';


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
