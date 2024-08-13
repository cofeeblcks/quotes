<?php

namespace App\Traits;

use App\Enums\OutputList;
use Illuminate\Database\Eloquent\Builder;

trait WithActionListBasicStart
{
    // Configuration
    public int $recordsPerPage = 25;
    public array $orderBy = [['id', 'asc']];
    public OutputList $output = OutputList::COLLECTION;

    // Filters
    public array|null $idsFilter = null;

    /**
     * Set a parameter
     * @param string $param
     * @param mixed $value
     * @return self
     */
    public function setParam(string $param, $value): self
    {
        $this->$param = $value;
        return $this;
    }

    /**
     * Run the query
     * @param Builder $builder
     */
    private function run(Builder $builder): mixed
    {
        $this->applyIdsFilter($builder);
        $this->applyOrderBy($builder);  
        switch ($this->output) {
            case OutputList::COLLECTION:
                $builder = $builder->get();
                break;
            case OutputList::PAGINATE:
                $className = $builder->getModel()->getTable();
                $builder = $builder->paginate($this->recordsPerPage,['*'], $className.'_page');
                break;
            case OutputList::ARRAY:
                $builder = $builder->get()->toArray();
                break;
        }
        return $builder;
    }

    /**
     * Apply order by
     * @param Builder $builder
     */
    private function applyOrderBy(Builder &$builder)
    {
        foreach ($this->orderBy as $order) {
            $order = is_array($order) ? $order : [$order, 'asc'];
            $builder->orderBy($order[0], $order[1]??'asc');
        }
    }

    /**
     * Apply ids filter
     * @param Builder $builder
     */
    private function applyIdsFilter(Builder &$builder)
    {
        $builder->where(function($query) {
            if (is_array($this->idsFilter)){
                $query->whereIn('id', $this->idsFilter);
            }
        });
    }
}
