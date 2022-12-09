<?php

namespace App\Service;

use App\Models\CancelRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FilterCancelRequestService
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
        $this->query = CancelRequest::query();
    }

    /**
     * set active filter
     *
     * @return FilterReturnRequestService
     */
    public function active(): FilterCancelRequestService
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
    public function byStore($stores): FilterCancelRequestService
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
    public function relations(array $relations): FilterCancelRequestService
    {
        $this->query->with($relations);
        return $this;
    }

    /**
     * set query sort ReturnRequest
     *
     * @param array $orders
     * @return FilterCancelRequestService
     */
    public function sortBy(array $orders = ['id' => 'desc']): FilterCancelRequestService
    {
        foreach ($orders as $orderBy => $CancelRequest) {
            $this->query->orderBy($orderBy, $CancelRequest);
        }

        return $this;
    }
    

    /**
     * get latest tags
     *
     * @return FilterCancelRequestService
     */
    public function latest(): FilterCancelRequestService
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
     * @return FilterCancelRequestService
     */
    public function take(int $total): FilterCancelRequestService
    {
        $this->query->take($total);
        return $this;
    }

    /**
     * get single CancelRequest
     *
     * @return CancelRequest
     */
    public function first(): CancelRequest
    {
        return $this->query->first();
    }

    /**
     * get single CancelRequest
     *
     * @return CancelRequest
     */
    public function firstOrFail(): CancelRequest
    {
        return $this->query->firstOrFail();
    }

    /**
     * get pending requests
     *
     * @return FilterReturnRequestService
     */
    public function pending(): FilterCancelRequestService
    {
        $this->query->where('status', CancelRequest::PENDING);
        return $this;
    }
}
