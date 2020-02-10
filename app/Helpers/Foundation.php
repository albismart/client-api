<?php

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Date;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

/**
 * Create a new Carbon instance for the current time.
 *
 * @param  \DateTimeZone|string|null $tz
 * @return \Illuminate\Support\Carbon
 */
function now($tz = null)
{
    return Date::now($tz);
}

/**
 * Get the available auth instance.
 *
 * @param  string|null  $guard
 * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
 */
function auth($guard = null)
{
    if (is_null($guard)) {
        return app(AuthFactory::class);
    }

    return app(AuthFactory::class)->guard($guard);
}

/**
 * Throw an HttpException with the given data if the given condition is true.
 *
 * @param  bool    $boolean
 * @param  int     $code
 * @param  string  $message
 * @param  array   $headers
 * @return void
 *
 * @throws \Symfony\Component\HttpKernel\Exception\HttpException
 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
 */
function abort_if($boolean, $code, $message = '', array $headers = [])
{
    if ($boolean) {
        abort($code, $message, $headers);
    }
}

/**
 * Get an instance of the current request or an input item from the request.
 *
 * @param  array|string  $key
 * @param  mixed   $default
 * @return \Illuminate\Http\Request|string|array
 */
function request($key = null, $default = null)
{
    if (is_null($key)) {
        return app('request');
    }

    if (is_array($key)) {
        return app('request')->only($key);
    }

    $value = app('request')->__get($key);

    return is_null($value) ? value($default) : $value;
}

function config_path($file)
{
    return base_path("config/{$file}");
}
