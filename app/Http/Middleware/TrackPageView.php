<?php

namespace App\Http\Middleware;

use App\Models\PageView;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrackPageView
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $response = $next($request);


        /*
        |--------------------------------------------------------------------------
        | ONLY GET
        |--------------------------------------------------------------------------
        */

        if (!$request->isMethod('GET')) {

            return $response;
        }


        /*
        |--------------------------------------------------------------------------
        | DO NOT TRACK ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $request->is('admin')
            ||
            $request->is('admin/*')
        ) {

            return $response;
        }


        /*
        |--------------------------------------------------------------------------
        | DO NOT TRACK ADMIN USER
        |--------------------------------------------------------------------------
        */

        if (
            auth()->check()
            &&
            auth()->user()->role === 'admin'
        ) {

            return $response;
        }


        /*
        |--------------------------------------------------------------------------
        | AJAX / JSON
        |--------------------------------------------------------------------------
        */

        if (
            $request->ajax()
            ||
            $request->expectsJson()
        ) {

            return $response;
        }


        /*
        |--------------------------------------------------------------------------
        | SUCCESSFUL HTML ONLY
        |--------------------------------------------------------------------------
        */

        if (
            $response->getStatusCode() < 200
            ||
            $response->getStatusCode() >= 400
        ) {

            return $response;
        }


        $contentType =
            strtolower(
                (string) $response->headers->get(
                    'Content-Type',
                    ''
                )
            );


        if (
            !str_contains(
                $contentType,
                'text/html'
            )
        ) {

            return $response;
        }


        /*
        |--------------------------------------------------------------------------
        | PATH
        |--------------------------------------------------------------------------
        */

        $path =
            '/' .
            ltrim(
                $request->path(),
                '/'
            );


        if (
            $path === '//'
            ||
            $path === ''
        ) {

            $path = '/';
        }


        /*
        |--------------------------------------------------------------------------
        | IGNORE FILE / SYSTEM PATHS
        |--------------------------------------------------------------------------
        */

        $ignoredPrefixes = [

            '/admin',

            '/storage',

            '/build',

            '/css',

            '/js',

            '/images',

            '/logo',

            '/favicon',
        ];


        foreach ($ignoredPrefixes as $prefix) {

            if (
                $path === $prefix
                ||
                str_starts_with(
                    $path,
                    $prefix . '/'
                )
            ) {

                return $response;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | VISITOR
        |--------------------------------------------------------------------------
        */

        $ip =
            (string) $request->ip();


        $visitorHash =
            hash(
                'sha256',
                implode(
                    '|',
                    [
                        $ip,

                        (string) $request->userAgent(),

                        (string) config('app.key'),
                    ]
                )
            );


        /*
        |--------------------------------------------------------------------------
        | LOCATION
        |--------------------------------------------------------------------------
        */

        $location =
            $this->locationForIp(
                $ip
            );


        /*
        |--------------------------------------------------------------------------
        | SAVE
        |--------------------------------------------------------------------------
        */

        try {

            PageView::create([

                'user_id' =>
                    auth()->id(),

                'path' =>
                    mb_substr(
                        $path,
                        0,
                        255
                    ),

                'route_name' =>
                    $request
                        ->route()
                        ?->getName(),

                'visitor_hash' =>
                    $visitorHash,

                'country_code' =>
                    $location['country_code'],

                'country_name' =>
                    $location['country_name'],

                'region_name' =>
                    $location['region_name'],

                'city' =>
                    $location['city'],

                'visited_at' =>
                    now(),
            ]);

        } catch (Throwable $exception) {

            /*
             * Analytics must never break DancePair.
             */
        }


        return $response;
    }


    /*
    |--------------------------------------------------------------------------
    | GEOLOCATION
    |--------------------------------------------------------------------------
    */

    private function locationForIp(
        string $ip
    ): array {

        $empty = [

            'country_code' => null,

            'country_name' => null,

            'region_name' => null,

            'city' => null,
        ];


        /*
        |--------------------------------------------------------------------------
        | LOCAL / PRIVATE IP
        |--------------------------------------------------------------------------
        */

        $publicIp =
            filter_var(
                $ip,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE
                |
                FILTER_FLAG_NO_RES_RANGE
            );


        if (!$publicIp) {

            return $empty;
        }


        /*
        |--------------------------------------------------------------------------
        | CACHE
        |--------------------------------------------------------------------------
        |
        | Only one geo lookup per visitor IP per day.
        |
        */

        $cacheKey =
            'dancepair_geo_' .
            hash(
                'sha256',
                $ip
            );


        try {

            return Cache::remember(

                $cacheKey,

                now()->addDay(),

                function () use (
                    $ip,
                    $empty
                ) {

                    try {

                        $response =
                            Http::acceptJson()
                                ->timeout(2)
                                ->get(
                                    'https://ipapi.co/'
                                    .
                                    rawurlencode($ip)
                                    .
                                    '/json/'
                                );


                        if (!$response->successful()) {

                            return $empty;
                        }


                        $data =
                            $response->json();


                        return [

                            'country_code' =>
                                isset(
                                    $data['country_code']
                                )
                                    ? strtoupper(
                                        mb_substr(
                                            $data['country_code'],
                                            0,
                                            2
                                        )
                                    )
                                    : null,

                            'country_name' =>
                                $data['country_name']
                                    ?? null,

                            'region_name' =>
                                $data['region']
                                    ?? null,

                            'city' =>
                                $data['city']
                                    ?? null,
                        ];

                    } catch (Throwable $exception) {

                        return $empty;
                    }
                }

            );

        } catch (Throwable $exception) {

            return $empty;
        }
    }
}