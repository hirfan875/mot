<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Banner;
use App\Http\Resources\CouponResource;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\HelpCenterResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\ProductResource;
use App\Models\HomepageSorting;
use App\Models\Page;
use App\Models\StoreQuestion;
use App\Service\ContactInquiryService;
use App\Service\ContactusService;
use App\Service\CouponService;
use App\Service\CurrencyService;
use App\Service\FlashDealService;
use App\Service\HelpCenterService;
use App\Service\LanguageService;
use App\Service\SliderService;
use App\Service\StoreQuestionService;
use App\Service\StoreService;
use App\Service\SubscribedUserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Resources\FlashDealResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Service\NotificationService;
use App\Models\RequestProduct;
//use App\Extensions\Response;
use App\Models\Product;


class HomeController extends BaseController
{
    const HOME_PAGE_CATEGORY_LIMIT = 6;

    public function index()
    {
        try {

            $contact_no = get_option('contact_no');
            $whatsapp = null;
            if($contact_no != null) {
                $contact_no = explode(',', $contact_no);
                $whatsapp = $contact_no[0];
            }
            $sliderService = new SliderService();
            $sliders = Banner::collection($sliderService->getHomePageSliders());

            $flashDealService = new FlashDealService();
            $flashDeals = FlashDealResource::collection($flashDealService->getDealsForHomePage(8));

            $sections = HomepageSorting::whereHas('sortable', function (Builder $query) {
                return $query->whereStatus(true);
            })->with('sortable')->where('sortable_type', 'App\Models\TrendingProduct')->orderBy('sort_order')->get();

            $trendingProducts = [];
            foreach ($sections as $sectionKey => $section) {
                $trendingProducts[$sectionKey]['id'] = $section->sortable_id;
                $trendingProducts[$sectionKey]['name'] = __($section->sortable->title);
                $trendingProducts[$sectionKey]['products'] = ProductResource::collection($section->sortable->get_products(12));
            }

            /*social media icons*/
            $socialIcons['facebook'] = get_option('social_facebook');
            $socialIcons['instagram'] = get_option('social_instagram');
            $socialIcons['twitter'] = get_option('social_twitter');
            $socialIcons['youtube'] = get_option('social_youtube');
            $socialIcons['linkedin'] = get_option('social_linkedin');
            $socialIcons['snapchat'] = get_option('social_snapchat');
            $socialIcons['gmail'] = get_option('targetemail');
            $socialIcons['whatsapp'] = null;
            if ($whatsapp != null) {
                $socialIcons['whatsapp'] = "https://api.whatsapp.com/send/?phone=" . trim(preg_replace('/[^0-9]/', '', $whatsapp)) . "&text&app_absent=0";
            }

        } catch (\Exception $exc) {
            return $this->sendError(__('Error'), __($exc->getMessage()));
        }
        
        if(get_option('app_home_template') == 'home'){
            $data = [
                'sliders' => $sliders,
                'flash_deals' => $flashDeals,
                'sections' => $trendingProducts,
                'social_icons' => $socialIcons,
            ];
        } else {
            $data = [
                'sliders' => $sliders,
                'flash_deals' => null,
                'sections' => null,
                'social_icons' => $socialIcons,
            ];
        }
        
        return $this->sendResponse($data, __('Data loaded successfully'));
    }

    public function newsletter(Request $request)
    {
        $subscribedService = new SubscribedUserService();
        if ($subscribedService->isAlreadySubscribed($request->email)) {
            return $this->sendError(__($request->email.' this user has already subscribed to our newsletter.'));
        }

        $subscribedService->create($request->all());

        $post_string = '{
        }';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://mallofturkeya.us5.list-manage.com/subscribe/post-json?u=6cf790f44a62588dc0e4becd4&id=5c5f26f180&c=jQuery19008415221768363639_1632727481111&b_6cf790f44a62588dc0e4becd4_5c5f26f180=&subscribe=Subscribe&_=1632727481113&EMAIL='.$request->email);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Content-Length: ' . strlen($post_string), 'Accept: application/json'));
        $result1 = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        $remove = str_replace('jQuery19008415221768363639_1632727481111(', '', $result1);
        $json_object = str_replace(')', '', $remove);
        $json1= json_decode($json_object,true);

        if(isset($json1['msg']) &&  explode('<a href="https', $json1['msg']) != null){
            $res = explode(' <a href="https', $json1['result']);
            $msg = explode(' <a href="https', $json1['msg']);

            $response['result'] = isset($res[0]) ? $res[0] : 'error';
            $response['msg'] = isset($msg[0]) ? $msg[0] : 'Unable to Submit Request';

            return $this->sendResponse(__($response['result']),__($response['msg']));
        }

        $response = [
            'result' => 'error',
            'msg' => 'Unable to Submit Request',
        ];

        return $this->sendError($response['msg'], __($response));

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.getresponse.com/v3/contacts',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
          "campaign": {
            "campaignId": "rRekU"
          },
          "email": "'.$request->EMAIL.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'X-Auth-Token: api-key lolibwrp4nrkrysoi9648b8aici67m2e',
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;

    }

    /**
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function page(string $slug)
    {
        $records = Page::where('slug', trim($slug))->first();

        if ($records == null) {
            return $this->sendError(__('Page not found'));
        }

        $pageData['title'] = $records->page_translates ? $records->page_translates->title : $records->title;
        $pageData['slug'] = $records->slug;
        $pageData['description'] = $records->page_translates ? $records->page_translates->data : $records->data;
        return $this->sendResponse($pageData, __('Data loaded successfully'));
    }

    /**
     * @param CurrencyService $currencyService
     * @return \Illuminate\Http\Response
     */
    public function getCurrencies(CurrencyService $currencyService)
    {
        $currencies = CurrencyResource::collection($currencyService->getAll());
        return $this->sendResponse($currencies, __('Data loaded successfully'));
    }

    public function getLanguages(LanguageService $languageService)
    {
        $languages = LanguageResource::collection($languageService->getActive());
        return $this->sendResponse($languages, __('Data loaded successfully'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeContactUs(Request $request, NotificationService $notificationService)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|numeric',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }
        try {
            $ContactService = new ContactusService();
            $customer_id = null;
            if (Auth('sanctum')->check()) {
                $customer_id = Auth('sanctum')->user()->id;
            }
            $contactUs = $ContactService->create($request->all() + ['customer_id' => $customer_id], $notificationService);

        } catch (\Exception $exc) {
            return $this->sendError(__('Unable to submit form.'), $exc->getMessage());
        }
        return $this->sendResponse($contactUs, __('Your Inquiry has been sent successfully !'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function requestProduct(Request $request, NotificationService $notificationService)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'email' => 'required|string|email|max:255',
            'phone' => 'required',
//            'prod_name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }
        try {

            $input = $request->all();
//            $ContactService = new ContactusService();
            $customer_id = null;
            if (Auth('sanctum')->check()) {
                $customer_id = Auth('sanctum')->user()->id;
            }

            $requestProduct = new RequestProduct();
            $requestProduct->name = $input['name'];
            $requestProduct->email = $input['email'];
            $requestProduct->phone = $input['phone'];
            $requestProduct->link = $input['link'];
            if (isset($input['image']) && count($input['image']) > 0) {
                $name = $request->file('image')[0]->getClientOriginalName();
                $path = $request->file('image')[0]->store('images');
                $requestProduct->image = $path;
            }
            $requestProduct->store_name = $input['store_name'];
            $requestProduct->product_type = $input['type'];
            $requestProduct->prod_name = $input['prod_name'];
//          $requestProduct->quantity = $input['quantity'];
            $requestProduct->prod_desc = $input['prod_desc'];
//          $requestProduct->comment = $input['comment'];
            $requestProduct->status = true;
            $requestProduct->save();

        } catch (\Exception $exc) {
            return $this->sendError(__('Unable to submit form.'), $exc->getMessage());
        }
        return $this->sendResponse($requestProduct, __('Your Inquiry has been sent successfully !'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveSeller(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|unique:stores',
            'email' => 'required|max:100|email|unique:store_staff|unique:stores',
            'password' => 'required|confirmed|min:6|max:20',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors());
        }
        try {
            $storeService = new StoreService();
            $store = $storeService->registerFromWeb($request->toArray());
        } catch (\Exception $exc) {
            return $this->sendError(__('Unable to submit form.'), $exc->getMessage());
        }
        return $this->sendResponse([], __('Seller has been registered successfully'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generalSettings(Request $request)
    {
        $currencyService = new CurrencyService();
        $languageService = new LanguageService();
        $notificationService = new NotificationService();

        $currencies = CurrencyResource::collection($currencyService->getAll());
        $languages = LanguageResource::collection($languageService->getActive());

        /*save device token information*/
        $deviceArray = $notificationService->setupUserDeviceArray($request->all());
        $notificationService->saveOrUpdateDeviceToken($deviceArray);

        /*contact us information*/
        $contactUs['email'] = get_option('targetemail');
        $contactUs['number'] = get_option('contact_no');
        $contactUs['address'] = get_option('address');
        
        $contactUs['whatsapp'] = null;
        if($contactUs['number'] != null) {
            $contact_no = explode(',', $contactUs['number']);
            $whatsapp = $contact_no[0];
            $contactUs['whatsapp'] = trim(preg_replace('/[^0-9]/', '', $whatsapp));
        }
           
        $data = [
            'currencies' => $currencies,
            'languages' => $languages,
            'contact_us' => $contactUs,
            'shipping_days' => get_option('shipping_days'),
            'return_days' => get_option('return_days'),
            'splash_banner' => get_option('app_splash_banner') ?? null,
            'myFatoorah_test_key' => get_option('myfatoorah_test_key') ?? null,
            'myFatoorah_production_key' => get_option('myfatoorah_production_key') ?? null,
            'top_notification' => get_option('top_notification_'.getCurrentLang()->code) ?? null,
            'home_template' => get_option('app_home_template') ? get_option('app_home_template'): 'home',
        ];

        return $this->sendResponse($data, __('Data loaded successfully'));
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function splashBanner()
    {
        $data = [
            'splash_banner' => get_option('app_splash_banner') ?? null,
        ];
        return $this->sendResponse($data, __('Data loaded successfully'));
    }
    

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getCoupons(Request $request)
    {
        try {

            $couponService = new CouponService();
            if(isset($request->product_id)) {
                $product = Product::find($request->product_id);
                $coupons = CouponResource::collection($couponService->getByProductId($product)->whereNotNull('coupon_code'));
            } else {
                $coupons = $couponService->getActive();
                $coupons = CouponResource::collection($coupons);
            }

        } catch (\Exception $exc) {
            return $this->sendError($exc->getMessage());
        }

        return $this->sendResponse($coupons, __('Data loaded successfully'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|void
     */
    public function getInquiries(Request $request)
    {
        if (!Auth('sanctum')->check()) {
            return $this->sendError(__('User not found'), []);
        }

        $customer = Auth('sanctum')->user();
        $contactService = new ContactInquiryService();
        $storeQuestionService = new StoreQuestionService();
        $contactInquiries = $contactService->getAll($customer->id);
        $storeQuestionInquiries = $storeQuestionService->getAll($customer->id);

        if (isset($request->type) && $request->type == 'contact-us') {
            return $this->sendResponse($contactInquiries->toArray(), __('Data loaded successfully'));
        }

        if (isset($request->type) && $request->type == 'store-question') {
            return $this->sendResponse($storeQuestionInquiries->toArray(), __('Data loaded successfully'));
        }

        $inquiries = array_merge($contactInquiries->toArray(), $storeQuestionInquiries->toArray());
        usort($inquiries, function ($a, $b) {
            return $a['created_at'] < $b['created_at'];
        });

        return $this->sendResponse($inquiries, __('Data loaded successfully'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getHelpCenter(Request $request)
    {
        try {
            $helpCenterService = new HelpCenterService();
            $helpCenters = $helpCenterService->getActive();
            $helpCenters = HelpCenterResource::collection($helpCenters);
        } catch (\Exception $exc) {
            return $this->sendError($exc->getMessage());
        }

        return $this->sendResponse($helpCenters, __('Data loaded successfully'));
    }
}
