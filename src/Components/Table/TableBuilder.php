<?php

namespace Cmsify\Cmsify\Components\Table;

use Closure;
use Cmsify\Cmsify\Components\Componentable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TableBuilder
{

    /**
     * @var ColumnSet
     */
    private $columnSet;

    /**
     * @var string
     */
    private $model;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $sortingColumn;

    /**
     * @var string
     */
    private $sortingDirection = 'asc';

    /**
     * @var array
     */
    private $relations;

    /**
     * TableBuilder constructor.
     */
    public function __construct()
    {
        $this->columnSet = new ColumnSet;
    }

    /**
     * @return JsonResponse
     */
    public function getData(): JsonResponse
    {
        $columns = $this->getColumnConfig();
        $request = request();
        $sortingColumn = $request->get('sortBy', $this->getSortingColumn());
        $sortingDirection = $request->get('sortDirection', $this->sortingDirection);

        $model = $this->model::query();

        if ($this->relations) {
            $model->with($this->relations);
        }

        if ($search = $request->get('filter')) {
            foreach ($columns->where('searchable', true) as $column) {
                $columnName = $column['key'];
                if (Str::contains($columnName, '.')) {
                    $explodedColumn = explode('.', $columnName);
                    $relationColumn = Arr::last($explodedColumn);
                    unset($explodedColumn[count($explodedColumn) - 1]);
                    $relation = implode('.', $explodedColumn);

                    $model->orWhereHas($relation, function ($query) use ($relationColumn, $search) {
                        $query->where($relationColumn, 'LIKE', '%' . $search . '%');
                    });
                    continue;
                }
                $model->orWhere($columnName, 'LIKE', '%' . $search . '%');
            }
        }

        $items = $model->orderBy($sortingColumn, $sortingDirection)->paginate(20);

        $itemsMapped = $items->getCollection()->map(function ($item) use ($columns) {
            $data = [];
            foreach ($columns as $column) {
                if ($column['modified_value']) {
                    $data[$column['key']] = $column['modified_value']($item);
                    continue;
                }
                $data[$column['key']] = $item->{$column['key']};
            }

            return $data;
        });

        return response()->json([
            'pagination' => new LengthAwarePaginator(
                $itemsMapped,
                $items->total(),
                $items->perPage(),
                $items->currentPage(), [
                    'path'  => request()->url(),
                    'query' => [
                        'page' => $items->currentPage()
                    ]
                ]
            ),
            'columns'    => $this->getColumnConfig(),
            'sort'       => [
                'column'    => $this->getSortingColumn(),
                'direction' => $this->sortingDirection !== 'asc',
            ]
        ]);
    }

    /**
     * @param array $relations
     * @return $this
     */
    public function relations(array $relations): self
    {
        $this->relations = $relations;

        return $this;
    }

    /**
     * @param string $column
     * @param bool $desc
     * @return TableBuilder
     */
    public function sort(string $column, bool $desc = false): TableBuilder
    {
        $this->sortingColumn = $column;
        $this->sortingDirection = $desc === true ? 'desc' : 'asc';

        return $this;
    }

    /**
     * @return string
     */
    public function getSortingColumn(): string
    {
        if (!$this->sortingColumn) {
            return $this->getColumnConfig()->first()['key'];
        }

        return $this->sortingColumn;
    }

    /**
     * @param Closure $columnSetClosure
     * @return $this
     */
    public function columns(Closure $columnSetClosure): self
    {
        $columnSetClosure($this->columnSet);

        return $this;
    }

    /**
     * @param string $model
     * @return TableBuilder
     */
    public function model(string $model): TableBuilder
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param string $url
     * @return TableBuilder
     */
    public function url(string $url): TableBuilder
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getColumnConfig(): Collection
    {
        return $this->columnSet->getColumns();
    }
}
