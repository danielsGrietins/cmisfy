<?php

namespace Cmsify\Cmsify\Components;

interface Componentable
{

    /**
     * @return array
     */
    public function build(): array;
}
