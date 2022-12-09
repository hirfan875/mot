<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Service\CropMediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CropMediaService $cropMediaService)
    {
        $validType = $cropMediaService->checkMediaType($request->toArray());
        if (!$validType) {
            return redirect()->route('seller.dashboard');
        }

        $imageData = $cropMediaService->getImageData($request->toArray());
        $all_sizes = $cropMediaService->getTypeAvailableSizes($request['type']);
        $filter_size = $all_sizes[0];
        $selected_size = $filter_size['slug'];

        if ($request->has('size') && !empty($request->size)) {
            $selected_size = $request->size;
            $filter_size = collect($all_sizes)->first(function ($row) use ($selected_size) {
                return $row['slug'] === $selected_size;
            });
        }

        return view('seller.media-crop.index', [
            'title' => __('Crop Image'),
            'all_sizes' => $all_sizes,
            'selected_size' => $selected_size,
            'filter_size' => $filter_size,
            'imageData' => $imageData,
            'request' => $request->toArray()
        ]);
    }

    /**
     * save crop image
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function cropImage(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'size' => 'required',
            'image_name' => 'required',
            'new_image' => 'required',
        ]);

        $this->createDirectory($request->size);
        $explodeImage = explode(";base64,", $request->new_image);
        $new_image = base64_decode($explodeImage[1]);
        $imagePath = $request->size . "/" . $request->image_name;
        Storage::put($imagePath, $new_image);

        return back()->with('success', __('Image cropped/saved successfully.'));
    }

    /**
     * create size directory
     *
     * @param string $directory
     * @return void
     */
    protected function createDirectory(string $directory)
    {
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
    }
}
