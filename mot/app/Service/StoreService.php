<?php

namespace App\Service;

use App\Models\Customer;
use App\Models\Store;
use App\Models\StoreQuestion;
use App\Models\StoreReview;
use Illuminate\Database\Eloquent\Collection;
use App\Models\StoreData;
use Illuminate\Support\Facades\Auth;
use App\Models\UserDevices;
use App\Service\NotificationService;

class StoreService {

    /**
     * create new store
     *
     * @param array $request
     * @return Store
     */
    public function create(array $request): Store {
        $store = new Store();

        $store->name = $request['name'];
        $store->phone = $request['phone'];
        $store->email = $request['email'];
        $store->type = $request['type'];
        $store->tax_id = $request['tax_id'];
        $store->commission = isset($request['commission']) ? $request['commission'] : null;
        $store->address = $request['address'];
        $store->city = $request['city'];
        $store->state = $request['state'];
        $store->country_id = $request['country'];
        $store->zipcode = $request['zipcode'];
        $store->tax_office = $request['tax_office'];
        $store->legal_name = $request['legal_name'];
        $store->iban = $request['iban'];
        $store->identity_no = $request['identity_no'];
        $store->status = true;
        $store->is_approved = Store::STATUS_PENDING;
        $store->save();

        // insert store first staff user
        $storeStaffService = new StoreStaffService();
        $storeStaffService->createOwner($request, $store);
        if($store->id){
            
        
        $storeData = StoreData::firstOrCreate(['store_id' => $store->id]);
        if (isset($request['banner'])) {
            $storeData->banner = Media::handle($request, 'banner', $storeData);
        }
        if (isset($request['logo'])) {
            $storeData->logo = Media::handle($request, 'logo', $storeData);
        }
        if (isset($request['remove_banner']) && $request['remove_banner'] == 'Yes') {
            $storeData->banner = Media::handle($request, 'banner', $storeData);
        }
        if (isset($request['remove_logo']) && $request['remove_logo'] == 'Yes') {
            $storeData->logo = Media::handle($request, 'logo', $storeData);
        }
        
        $storeData->save();
        }
//        $curl = curl_init();
//
//        curl_setopt_array($curl, array(
//          CURLOPT_URL => 'https://api.getresponse.com/v3/shops',
//          CURLOPT_RETURNTRANSFER => true,
//          CURLOPT_ENCODING => '',
//          CURLOPT_MAXREDIRS => 10,
//          CURLOPT_TIMEOUT => 0,
//          CURLOPT_FOLLOWLOCATION => true,
//          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//          CURLOPT_CUSTOMREQUEST => 'POST',
//          CURLOPT_POSTFIELDS =>'{
//        "name": "'.$request['name'].'",
//        "locale": "TR",
//        "currency": "TRY"
//        }',
//          CURLOPT_HTTPHEADER => array(
//            'X-Auth-Token: api-key lolibwrp4nrkrysoi9648b8aici67m2e',
//            'Content-Type: application/json'
//          ),
//        ));
//
//        $response = curl_exec($curl);
//
//        curl_close($curl);
//        echo $response;


        return $store;
    }

    /**
     * update store
     *
     * @param Store $store
     * @param array $request
     * @return Store
     */
    public function update(Store $store, array $request): Store {
        $store->name = $request['name'];
        if (isset($request['type'])) {
            $store->type = $request['type'];
        }
        $store->legal_name = $request['legal_name'];
        $store->email = $request['store_email'];
        $store->company_website = isset($request['company_website']) ? $request['company_website']: null;
        $store->social_media = isset($request['social_media']) ? $request['social_media']: null;
        $store->tax_id = $request['tax_id'];
        if (isset($request['commission'])) {
            $store->commission = $request['commission'];
        }
        $store->address = $request['address'];
        $store->city = $request['city'];
        $store->state = $request['state'];
        $store->country_id = $request['country'];
        $store->zipcode = $request['zipcode'];
        $store->phone = $request['phone'];
        $store->mobile = isset($request['mobile']) ? $request['mobile']: null;
        $store->tax_office = $request['tax_office'];
        $store->bank_name = isset($request['bank_name']) ? $request['bank_name']: null;
        $store->account_title = isset($request['account_title']) ? $request['account_title']: null;
        $store->iban = $request['iban'];
        $store->identity_no = $request['identity_no'];
        $store->tax_id_type = isset($request['tax_id_type']) ? $request['tax_id_type']: '';
        if (isset($request['seller_id'])) {
            $store->seller_id = $request['seller_id'];
        }
        if (isset($request['trendyol_approved'])) {
            $store->trendyol_approved = $request['trendyol_approved'];
        }
        if (isset($request['trendyol_key'])) {
            $store->trendyol_key = $request['trendyol_key'];
        }
        if (isset($request['trendyol_secret'])) {
            $store->trendyol_secret = $request['trendyol_secret'];
        }
        if (isset($request['legal_papers'])) {

            $store->legal_papers = Media::handle($request, 'legal_papers', $store);
        }        
        $store->signature = isset($request['signature']) ? $request['signature']: null;
        $store->goods_services = isset($request['goods_services']) ? $request['goods_services']: null;
        
        $store->save();

        $storeData = StoreData::whereStoreId($store->id)->first();
        if (isset($request['banner'])) {
            $storeData->banner = Media::handle($request, 'banner', $storeData);
        }
        if (isset($request['logo'])) {

            $storeData->logo = Media::handle($request, 'logo', $storeData);
        }
        if (isset($request['remove_banner']) && $request['remove_banner'] == 'Yes') {
            $storeData->banner = Media::handle($request, 'banner', $storeData);
        }
        if (isset($request['remove_logo']) && $request['remove_logo'] == 'Yes') {
            $storeData->logo = Media::handle($request, 'logo', $storeData);
        }
        $storeData->save();

        return $store;
    }

    /**
     * create store question
     *
     * @param array $data
     * @return StoreQuestion
     */
    public function createQuestion(array $data): StoreQuestion {
        $storeQuestion = new StoreQuestion();

        $storeQuestion->store_id = $data['store_id'];
        $storeQuestion->name = $data['name'];
        $storeQuestion->email = $data['email'];
        $storeQuestion->phone = $data['phone'];
        $storeQuestion->message = $data['message'];
        if (isset($data['customer_id']) && $data['customer_id'] != null) {
            $storeQuestion->customer_id = $data['customer_id'];
            $customer_id = $data['customer_id'];
                    $userDevice = UserDevices::where('customer_id', $data['customer_id'])->where('is_general_notifications',true)->latest()->first();
        } else {

            if(isset($data['device_token'])){
            $userDevice = UserDevices::where('token', $data['device_token'])->where('is_general_notifications',true)->latest()->first();
             }
        }
     
        $storeQuestion->save();
        
                if(isset($userDevice->token)){

                    $title = _("Asked a Question");
                    $description = __("we'll get back to you soon notification will be received your Question");
                    $type = 'general' ;
                    $lang_id = 1;
                    $token = $userDevice->token;

                    $message = [
                        'title' => $title,
                        'description' => $description,
                        'customer_id' => $customer_id,
                        'type' => $type,
                        'language_id' => $lang_id,
                        'token' => $token,
                    ];
                    $screenA = '/inquiry';
                    $notificationService = new NotificationService();
                    $notificationService->saveNotifications($message);
                    $notificationService->sendNotification($token, $message,$screenA);
                }

        return $storeQuestion;
    }

    /**
     * create store question
     *
     * @param array $data
     * @return StoreReview
     */
    public function createFeedback(array $data): StoreReview {
        $storeReview = new StoreReview();

        $storeReview->store_id = $data['store_id'];
        $storeReview->customer_id = $data['customer_id'];
        $storeReview->language_id = $data['language_id'];
        if (isset($data['store_order_id']) && $data['store_order_id'] != null) {
            $storeReview->store_order_id = $data['store_order_id'];
        }
        $storeReview->is_approved = $data['is_approved'];
        $storeReview->rating = $data['rating'];
        $storeReview->comment = $data['comment'];
        $storeReview->save();
        return $storeReview;
    }

    /**
     * get store by slug
     *
     * @param string $slug
     * @return Store
     */
    public function getStore(string $slug): ?Store {
        $store = Store::with(['reviews' => function ($query) {
                        $query->where('is_approved', true)->with('customer');
                    }])->where('is_approved', true)->where('slug', $slug)->first();

        return $store;
    }

    /**
     * @param int $id
     * @return Store|null
     */
    public function getById(int $id): ?Store {
        return Store::find($id);
    }

    /**
     * @param array $request
     * @return Store
     */
    public function registerFromWeb(array $request): Store {
        $store = new Store();
        $store->name = $request['name'];
        $store->phone = $request['phone'];
        $store->email = $request['email'];
        $store->status = true;
        $store->is_approved = Store::STATUS_PENDING;
        $store->save();
        //inserting categories
        if (isset($request['categories']) && sizeof($request['categories']) > 0) {
            $store->categories()->attach($request['categories']);
        }
        // insert store first staff user
        $storeStaffService = new StoreStaffService();
        $storeStaffService->createOwner($request, $store);

        // insert store first staff user
        $storeDataService = new StoreDataService();
        $storeDataService->createData($request, $store);

        return $store;
    }

    /**
     * @return Store[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): Collection {
        return Store::approved()->where('status', true)->with('country')->latest()->get();
    }

    /**
     * @param null $perPage
     * @return mixed
     */
    public function getAllStores($perPage = null) {
        $stores = Store::where('status', true)->approved()->with('store_data')
//                ->whereHas('products', function ($query) {
//                    $query->Active();
//                })
                        ->paginate($perPage);

        return $stores;
    }

    /**
     * @param Store $store
     * @param Customer $customer
     * @return bool
     */
    public function isAbleToReview(Store $store, Customer $customer)
    {
        $isAbleToReview = false;
        $orderService = new FilterOrderService();
        $orders = $orderService->byCustomer($customer->id)->byStore($store->id)->get();
        $customerStoreOrders = $store->reviews()->where('customer_id', $customer->id)->get();
        if ($orders->count() > 0 && $customerStoreOrders->count() == 0) {
            $isAbleToReview = true;
        }
        return $isAbleToReview;
    }

}
