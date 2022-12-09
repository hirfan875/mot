<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Media;

class MediaSettingsController extends Controller
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
        return view('admin.media-settings.index', [
            'title' => __('Media Settings')
        ]);
    }

    // form process
    public function formProcess(Request $request)
    {
        update_option('thumbnail_size_width', $request->thumbnail_size_width);
        update_option('thumbnail_size_height', $request->thumbnail_size_height);
        update_option('medium_size_width', $request->medium_size_width);
        update_option('medium_size_height', $request->medium_size_height);
        update_option('large_size_width', $request->large_size_width);
        update_option('large_size_height', $request->large_size_height);

        $media_placeholder = get_option('media_placeholder');

        // remove placeholder
        if ( $request->remove_media_placeholder == 'Yes' ) {
            delete_option('media_placeholder');
            Media::delete($media_placeholder);
        }

        // image processsing
        if ( $request->hasFile('media_placeholder') ) {

            // delete old image
            Media::delete($media_placeholder);

            $fileUploaded = Media::upload($request->file('media_placeholder'));
            update_option('media_placeholder', $fileUploaded);
        }

        return back()->with('success', __('Settings updated successfully.'));
    }
}
