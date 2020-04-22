<?php

namespace Cmsify\Cmsify\Registries;

use Cmsify\Cmsify\Components\FormElements\Form;
use Cmsify\Cmsify\Components\FormElements\Hidden;
use Cmsify\Cmsify\Components\FormElements\Text;
use Cmsify\Cmsify\Components\FormElements\Textarea;
use Cmsify\Cmsify\Components\Relations\HasMany;
use Cmsify\Cmsify\Components\Table\Table;

class ComponentRegistry
{

    /**
     * @return array
     */
    public function getList(): array
    {
        return [
            'text'     => Text::class,
            'hidden'   => Hidden::class,
            'textarea' => Textarea::class,
            'form'     => Form::class,
            'hasMany'  => HasMany::class,
            'table'    => Table::class,
        ];
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
