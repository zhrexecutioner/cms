<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Closure;


class fangshua
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    		$fangshuanum=DB::table('fangshua')->get()->toArray();
    		$newfangshuanum=$fangshuanum[0]->fangshuanum;
    		$fangshuatime=DB::table('fangshua')->get()->toArray();
    		$newfangshuatime=$fangshuatime[0]->fangshuatime;
            $uip = $_SERVER['SERVER_ADDR'];
            $path = $_SERVER['REQUEST_URI'];
            $mPath = substr(md5($path), 0, 10);
            $redis_key = 'str:' . $mPath . ':' . $uip;
            $num = Redis::incr($redis_key);
            Redis::expire($redis_key, $newfangshuatime);
            if ($num > $newfangshuanum) {
                $response = [
                    'errCode' => 45019
                    , 'errMsg' => '访问过于频繁'
                    , 'num' => $num
                ];
                Redis::expire($redis_key, $newfangshuatime);
                echo json_encode($response,JSON_UNESCAPED_UNICODE);die;
            }
        return $next($request);


    }


}

