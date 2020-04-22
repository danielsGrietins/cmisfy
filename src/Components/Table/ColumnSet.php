<?php

namespace Cmsify\Cmsify\Components\Table;

use Closure;
use Illuminate\Support\Collection;

class ColumnSet
{

    /**
     * @var Collection
     */
    private $columns;

    public function __construct()
    {
        $this->columns = collect([]);
    }

    /**
     * @param string $column
     * @return $this
     */
    public function add(string $column, string $label = null): self
    {
        $this->columns->push([
            'key'            => $column,
            'label'          => $label ?: $column,
            'sortable'       => true,
            'searchable'     => true,
            'html'           => false,
            'modified_value' => null,
        ]);

        return $this;
    }

    /**
     * @param Closure $closure
     * @return $this
     */
    public function edit(Closure $closure): self
    {
        $columnKey = $this->columns->last()['key'];

        $this->columns = $this->columns->map(function (array $column) use ($closure, $columnKey) {
            if ($column['key'] === $columnKey) {
                $column['modified_value'] = $closure;
            }

            return $column;
        });

        return $this;
    }

    /**
     * @param bool $html
     * @return $this
     */
    public function html(bool $html = true): self
    {
        $columnKey = $this->columns->last()['key'];

        $this->columns = $this->columns->map(function (array $column) use ($html, $columnKey) {
            if ($column['key'] === $columnKey) {
                $column['html'] = $html;
            }

            return $column;
        });

        return $this;
    }

    /**
     * @param bool $isSearchable
     * @return $this
     */
    public function searchable(bool $isSearchable = true): self
    {
        $columnKey = $this->columns->last()['key'];

        $this->columns = $this->columns->map(function (array $column) use ($isSearchable, $columnKey) {
            if ($column['key'] === $columnKey) {
                $column['searchable'] = $isSearchable;
            }

            return $column;
        });

        return $this;
    }

    /**
     * @param bool $isSortable
     * @return $this
     */
    public function sortable(bool $isSortable = true): self
    {
        $columnKey = $this->columns->last()['key'];

        $this->columns = $this->columns->map(function (array $column) use ($isSortable, $columnKey) {
            if ($column['key'] === $columnKey) {
                $column['sortable'] = $isSortable;
            }

            return $column;
        });

        return $this;
    }

    /**
     * @return Collection
     */
    public function getColumns(): Collection
    {
        return $this->columns;
    }
}
