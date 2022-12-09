<?php

namespace App\Extensions;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Response as BaseResponse;
use \Illuminate\Http\Request;

/**
 * Class Response
 * @package App\Extensions
 */
class Response extends BaseResponse
{

    /**
     * @param string|null $view
     * @param array $data
     * @param Request|null $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|BaseResponse
     * @throws \Exception
     */
    public static function success(string $view = null, $data = [], Request $request = null)
    {
        if (is_object($data)) {
            $data = $data->toArray(); // we need to call custom toArray on each object so we can filter out unwanted fields going to api
        }
        $logger = getLogger();
//        $logger->debug('Success ', $data);
        if ($request && $request->expectsJson()) {
            return response([
                'success' => true,
                'data' => $data
            ]);
        }
        if ($view) {
            return view($view, $data);
        }
        throw new \Exception('View not supplied');

    }

    /**
     * This method has too many parameters.
     * Number of parameters should be 3
     * or less. Anything more than
     * that is not ideal
     * @param string $view
     * @param string $message
     * @param $error
     * @param Request|null $request
     * @param int $error_code
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|BaseResponse
     * @throws \Throwable
     *
     */
    public static function error(string $view, string $message,$error, Request $request = null, $error_code = 422)
    {
        if ($request && !$request->expectsJson()) {
            return self::sendToView($error, $view);
        }
        return self::sendToJson($error_code, $message, $request);
    }

    public static function redirect(string $redirectUrl, Request $request = null, $with = [])
    {
        if ($request && $request->wantsJson()) {
            $data = ['success' => 'true'];
            $data = array_merge($data, $with);
            return new Response($data);
        }
        $redirectResponse = redirect($redirectUrl);
        foreach ($with as $key => $val) {
            $redirectResponse->with($key, $val);
        }
        return $redirectResponse;
    }

    public static function back(array $with = [])
    {
        return redirect()->back()->with($with);
    }

    /**
     * @param $error
     * @param string $view
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Throwable
     */
    public static function sendToView($error, string $view)
    {
        if ($error instanceof \Throwable) {
            throw $error;
        }
        return view($view);
    }

    /**
     * @param int $error_code
     * @param string $message
     * @param $errors
     * @param Request|null $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|BaseResponse
     */
    public static function sendToJson(int $error_code, string $message, ?Request $request)
    {
        $data = [
            'success' => false,
            'error_code' => $error_code,
            'message' => $message
        ];

        $logger = getLogger();
        $logger->error($message);
        if ($request && $request->expectsJson()) {
            return response($data, $error_code);
        }

    }
}

