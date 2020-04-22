<?php

namespace Cmsify\Cmsify\Components\FormElements;

class Hidden extends AbstractInput
{

    /**
     * @var string
     */
    protected $componentName = 'hidden-input';

    /**
     * Hidden constructor.
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
