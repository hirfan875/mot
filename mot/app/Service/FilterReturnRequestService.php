<?php

namespace App\Service;

use App\Models\ReturnRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FilterReturnRequestService
{
    /**
     * The base query builder instance.
     *
     * @var Builder
     */
    protected $query;

    /**
     * Create a new filter tags instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->query = ReturnRequest::query();
    }

    /**
     * set active filter
     *
     * @return FilterReturnRequestService
     */
    public function active(): FilterReturnRequestService
    {
        $this->query->active();
        return $this;
    }

    /**
     * set store filter
     *
     * @param int|array $stores
     * @return FilterReturnRequestService
     */
    public function byStore($stores): FilterReturnRequestService
    {
        if (!is_array($stores)) {
            $stores = [$stores];
        }

        $this->query->whereHas('store_order', function (Builder $query) use ($stores) {
            $query->whereIn('store_id', $stores);
        });
        return $this;
    }

    /**
     * set query relations
     *
     * @param array $relations
     * @return FilterReturnRequestService
     */
    public function relations(array $relations): FilterReturnRequestService
    {
        $this->query->with($relations);
        return $this;
    }

    /**
     * set query sort ReturnRequest
     *
     * @param array $orders
     * @return FilterReturnRequestService
     */
    public function sortBy(array $orders = ['id' => 'desc']): FilterReturnRequestService
    {
        foreach ($orders as $orderBy => $ReturnRequest) {
            $this->query->orderBy($orderBy, $ReturnRequest);
        }

        return $this;
    }

    /**
     * get latest tags
     *
     * @return FilterReturnRequestService
     */
    public function latest(): FilterReturnRequestService
    {
        $this->query->latest();
        return $this;
    }

    /**
     * get filtered tags collection
     *
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->query->get();
    }

    /**
     * get filtered tags collection with pagination
     *
     * @param null $perPage
     * @param string[] $columns
     * @param string $pageName
     * @param null $page
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator
    {
        return $this->query->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * get filtered tags count
     *
     * @return int
     */
    public function count(): int
    {
        return $this->query->count();
    }

    /**
     * get number of orders
     *
     * @param int $total
     * @return FilterReturnRequestService
     */
    public function take(int $total): FilterReturnRequestService
    {
        $this->query->take($total);
        return $this;
    }

    /**
     * get single ReturnRequest
     *
     * @return ReturnRequest
     */
    public function first(): ReturnRequest
    {
        return $this->query->first();
    }

    /**
     * get single ReturnRequest
     *
     * @return ReturnRequest
     */
    public function firstOrFail(): ReturnRequest
    {
        return $this->query->firstOrFail();
    }
}
