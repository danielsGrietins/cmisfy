<?php

namespace Cmsify\Cmsify\Components\Table;

use Closure;
use Cmsify\Cmsify\Components\Componentable;

class Table implements Componentable
{

    /**
     * @var TableBuilder
     */
    private $tableBuilder;

    public function __construct(TableBuilder $tableBuilder)
    {
        $this->tableBuilder = $tableBuilder;
    }

    /**
     * @return array
     */
    public function build(): array
    {
        return [
            'component_name' => 'cmsify-table',
            'columns' => $this->tableBuilder->getColumnConfig()
        ];
    }
}
