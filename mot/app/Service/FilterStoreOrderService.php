<?php

namespace App\Service;

use App\Models\StoreOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use DB;

class FilterStoreOrderService
{
    /**
     * The base query builder instance.
     *
     * @var Builder
     */
    protected $query;

    /**
     * @var bool
     */
    protected $includeUninitiated;

    /**
     * Create a new filter tags instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->includeUninitiated = false;
        $this->query = StoreOrder::query();
    }

    /**
     * set active filter
     *
     * @return FilterStoreOrderService
     */
    public function active(): FilterStoreOrderService
    {
        $this->query->active();
        return $this;
    }

    /**
     * set store filter
     *
     * @param int|array $stores
     * @return FilterStoreOrderService
     */
    public function byStore($stores): FilterStoreOrderService
    {
        if (!is_array($stores)) {
            $stores = [$stores];
        }

        $this->query->whereIn('store_id', $stores);
        return $this;
    }
    
    public function byDate($startDate,$endDate): FilterStoreOrderService
    {
        $this->query->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        return $this;
    }
    
    public function byGroup(): FilterStoreOrderService
    {
        $this->query->groupBy(DB::raw('DATE(created_at)'));
        return $this;
    }
    
     /**
     * set store filter
     *
     * @param int $status
     * @return FilterStoreOrderService
     */
    public function byStatus($status): FilterStoreOrderService
    {
        if (!is_array($status)) {
            $status = [$status];
        }

        $this->query->whereIn('status', $status);
        return $this;
    }

    /**
     * set customer filter
     *
     * @param int|array $customers
     * @return FilterOrderService
     */
    public function byCustomer($customers): FilterStoreOrderService
    {
        if (!is_array($customers)) {
            $customers = [$customers];
        }

        $this->query->whereHas('order', function (Builder $query) use ($customers) {
            return $query->whereIn('customer_id', $customers);
        });
        return $this;
    }

    /**
     * set customer order archive filter
     *
     * @param $archiveStatus
     * @return $this
     */
    public function byArchive($archiveStatus): FilterStoreOrderService
    {
        $this->query->where('is_archived', $archiveStatus);
        return $this;
    }


    /**
     * set product filter
     *
     * @param int|array $product_ids
     * @return FilterStoreOrderService
     */
    public function byProduct($product_ids): FilterStoreOrderService
    {
        if (!is_array($product_ids)) {
            $product_ids = [$product_ids];
        }

        $this->query->whereHas('order_items', function (Builder $query) use ($product_ids) {
            return $query->whereIn('product_id', $product_ids);
        });
        return $this;
    }
    
    public function byCategory($category_ids): FilterStoreOrderService
    {
        if (!is_array($category_ids)) {
            $category_ids = [$category_ids];
        }

        $this->query->whereHas('order_items.product.categories', function (Builder $query) use ($category_ids) {
            return $query->whereIn('category_id', $category_ids);
        });
        return $this;
    }
    
    public function byCoupon(): FilterStoreOrderService
    {
        $this->query->whereHas('order', function (Builder $query) {
            return $query->whereNotNull('coupon_id');
        });
        return $this;
    }

    /**
     * set query relations
     *
     * @param array $relations
     * @return FilterStoreOrderService
     */
    public function relations(array $relations): FilterStoreOrderService
    {
        $this->query->with($relations);
        return $this;
    }

    /**
     * set query sort StoreOrder
     *
     * @param array $orders
     * @return FilterStoreOrderService
     */
    public function sortBy(array $orders = ['order_number' => 'desc']): FilterStoreOrderService
    {
        foreach ($orders as $orderBy => $StoreOrder) {
            $this->query->orderBy($orderBy, $StoreOrder);
        }

        return $this;
    }

    /**
     * get latest tags
     *
     * @return FilterStoreOrderService
     */
    public function latest(): FilterStoreOrderService
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
        if (!$this->includeUninitiated) {
            $this->excludeUninitiated();
        }
        $this->query->orderBy('created_at', 'desc');
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
        if (!$this->includeUninitiated) {
            $this->excludeUninitiated();
        }
        return $this->query->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * get filtered tags count
     *
     * @return int
     */
    public function count(): int
    {
        if (!$this->includeUninitiated) {
            $this->excludeUninitiated();
        }
        return $this->query->count();
    }

    /**
     * get number of orders
     *
     * @param int $total
     * @return FilterStoreOrderService
     */
    public function take(int $total): FilterStoreOrderService
    {
        $this->query->take($total);
        return $this;
    }

    /**
     * get single StoreOrder
     *
     * @return StoreOrder
     */
    public function first(): StoreOrder
    {
        return $this->query->first();
    }

    /**
     * @return $this
     */
    protected function excludeUninitiated()
    {
        $this->query->whereNotIn('status' , [StoreOrder::UNIITIATED_ID, StoreOrder::CONFIRMED_ID, StoreOrder::TERMINATED_ID]);
        return $this;
    }

    /**
     * get single StoreOrder
     *
     * @return StoreOrder
     */
    public function firstOrFail(): StoreOrder
    {
        return $this->query->firstOrFail();
    }
    
    /**
     * @return $this
     */
    public function orderStatus()
    {
        $this->query->Where('status' , StoreOrder::DELIVERED_ID)->orWhere('status' ,  StoreOrder::SHIPPED_ID);
        return $this;
    }
}
