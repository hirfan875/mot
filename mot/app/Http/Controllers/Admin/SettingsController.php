<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\Media;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    // show all records
    public function showForm()
    {
        return view('admin.settings.index', [
            'title' => __('Settings')
        ]);
    }

    // form process
    public function formProcess(Request $request)
    {

        /*general settings*/
        update_option('targetemail', $request->targetemail);
        update_option('currency_api', $request->currency_api);
        update_option('contact_no', $request->contact_no);
        update_option('address', $request->address);
        update_option('shipping_days', $request->shipping_days);
        update_option('return_days', $request->return_days);
        update_option('shipping_flat_rate', $request->shipping_flat_rate);

        /*logo*/
        if ($request->hasFile('logo')) {
            // delete an old image
            $logo = get_option('logo');
            Media::delete($logo);

            $fileUploaded = Media::upload($request->file('logo'), true, true, 'image');
            update_option('logo', $fileUploaded);
        }

        /*mobile splash banner*/
        if ($request->hasFile('app_splash_banner')) {
            // delete an old image
            $app_splash_banner = get_option('app_splash_banner');
            Media::delete($app_splash_banner);

            $bannerFileUploaded = Media::upload($request->file('app_splash_banner'), true, true, 'image');
            update_option('app_splash_banner', $bannerFileUploaded);
        }

        
        update_option('myfatoorah_test_key', $request->myfatoorah_test_key);
        update_option('myfatoorah_production_key', $request->myfatoorah_production_key);
        update_option('app_home_template', $request->app_home_template);
        /*social media*/
        update_option('social_facebook', $request->social_facebook);
        update_option('social_instagram', $request->social_instagram);
        update_option('social_twitter', $request->social_twitter);
        update_option('social_pinterest', $request->social_pinterest);
        update_option('social_snapchat', $request->social_snapchat);
        update_option('social_youtube', $request->social_youtube);
        update_option('social_linkedin', $request->social_linkedin);

        /*meta tags */
        foreach (getLocaleList() as $row) {
            $meta_title = 'meta_title_' . $row->code;
            $meta_description = 'meta_description_' . $row->code;
            $meta_keywords = 'meta_keywords_' . $row->code;
            update_option('meta_title_' . $row->code, $request->$meta_title);
            update_option('meta_description_' . $row->code, $request->$meta_description);
            update_option('meta_keywords_' . $row->code, $request->$meta_keywords);
        }

        /*meta tags */
        foreach (getLocaleList() as $row) {
            $top_notification = 'top_notification_' . $row->code;
            update_option('top_notification_' . $row->code, $request->$top_notification);
        }

        return back()->with('success', __('Settings updated successfully.'));
    }
}
