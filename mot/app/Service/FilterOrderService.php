<?php

namespace App\Service;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use DB;
use App\Models\StoreOrder;

class FilterOrderService
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
        $this->query = Order::query();
    }

    /**
     * set active filter
     *
     * @return FilterStoreOrderService
     */
    public function active(): FilterOrderService
    {
        $this->query->active();
        return $this;
    }

    /**
     * set customer filter
     *
     * @param int|array $customers
     * @return FilterOrderService
     */

    public function byDate($startDate,$endDate): FilterOrderService
    {
        $this->query->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        return $this;
    }

    public function byGroup(): FilterOrderService
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
    public function byStatus($status): FilterOrderService
    {
        if (!is_array($status)) {
            $status = [$status];
        }

        $this->query->whereIn('status', $status);
        return $this;
    }


    public function byCustomer($customers): FilterOrderService
    {
        if (!is_array($customers)) {
            $customers = [$customers];
        }

        $this->query->whereIn('customer_id', $customers);
        return $this;
    }

    /**
     * set customer order archive filter
     *
     * @param $archiveStatus
     * @return $this
     */
    public function byArchive($archiveStatus): FilterOrderService
    {
        $this->query->where('is_archived', $archiveStatus);
        return $this;
    }


    /**
     * set store filter
     *
     * @param int|array $stores
     * @return FilterOrderService
     */
    public function byStore($stores): FilterOrderService
    {
        if (!is_array($stores)) {
            $stores = [$stores];
        }

        $this->query->whereHas('store_orders', function (Builder $query) use ($stores) {
            return $query->whereIn('store_id', $stores);
        });
        return $this;
    }

    /**
     * set product filter
     *
     * @param int|array $product_ids
     * @return FilterOrderService
     */
    public function byProduct($product_ids): FilterOrderService
    {
        if (!is_array($product_ids)) {
            $product_ids = [$product_ids];
        }

        $this->query->whereHas('order_items', function (Builder $query) use ($product_ids) {
            return $query->whereIn('product_id', $product_ids);
        });
        return $this;
    }

    public function byCategory($category_ids): FilterOrderService
    {
        if (!is_array($category_ids)) {
            $category_ids = [$category_ids];
        }

        $this->query->whereHas('order_items.product.categories', function (Builder $query) use ($category_ids) {
            return $query->whereIn('category_id', $category_ids);
        });
        return $this;
    }

    /**
     * set query relations
     *
     * @param array $relations
     * @return FilterOrderService
     */
    public function relations(array $relations): FilterOrderService
    {
        $this->query->with($relations);
        return $this;
    }

    /**
     * set query sort order
     *
     * @param array $orders
     * @return FilterOrderService
     */
    public function sortBy(array $orders = ['order_number' => 'desc']): FilterOrderService
    {
        foreach ($orders as $orderBy => $order) {
            $this->query->orderBy($orderBy, $order);
        }

        return $this;
    }

    public function byCoupon(): FilterOrderService
    {
        $this->$query->whereNotNull('coupon_id');
        return $this;
    }

    /**
     * get latest tags
     *
     * @return FilterOrderService
     */
    public function latest(): FilterOrderService
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
     * get filtered tags collection
     *
     * @return Collection
     */
    public function getUninitiated(): Collection
    {
        if (!$this->includeUninitiated) {
            $this->includeUninitiated();
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
     * @return FilterOrderService
     */
    public function take(int $total): FilterOrderService
    {
        $this->query->take($total);
        return $this;
    }

    /**
     * get single order
     *
     * @return Order
     */
    public function first(): Order
    {
        return $this->query->first();
    }
    
     /**
     * @return $this
     */
    protected function excludeUninitiated()
    {
        $this->query->whereNotIn('status' , [Order::UNIITIATED_ID, Order::CONFIRMED_ID, Order::TERMINATED_ID]);
        return $this;
    }
    
     /**
     * @return $this
     */
    protected function includeUninitiated()
    {
        $this->query->whereIn('status' , [Order::UNIITIATED_ID, Order::CONFIRMED_ID]);
        return $this;
    }

    /**
     * get single order
     *
     * @return Order
     */
    public function firstOrFail(): Order
    {
        return $this->query->firstOrFail();
    }

    /**
     * @return Order
     */
    public function returned()
    {
        $this->query->where(function ($subQuery) {
            $subQuery->where('status', StoreOrder::RETURN_ACCEPTED_ID)->orWhereHas('order_items.return_order_items');
        });
        return $this;
    }

    /**
     * @return Order
     */
    public function cancellationRequested()
    {
        $this->query->where(function ($subQuery) {
            $subQuery->where('status', StoreOrder::CANCEL_REQUESTED_ID)->orWhereHas('cancel_requests');
        });
        return $this;
    }
}
