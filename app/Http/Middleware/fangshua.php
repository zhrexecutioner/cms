<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Redis;

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
            # 用户ip
            $uip = $_SERVER['SERVER_ADDR'];
            # 访问的接口
            $path = $_SERVER['REQUEST_URI'];
            # 加密路由
            $mPath = substr(md5($path), 0, 10);
            #
            $redis_key = 'str:' . $mPath . ':' . $uip;
            # incr   默认+1  返回次数
            $num = Redis::incr($redis_key);         //  储存           用户+访问的路由
            Redis::expire($redis_key, 60);
            # 一分钟 20 次   上限
            if ($num > 1) {
                # 防刷
                $response = [
                    'errCode' => 45019
                    , 'errMsg' => '访问过于频繁'
                    , 'num' => $num
                ];


                # 10分钟
                Redis::expire($redis_key, 60);
                echo json_encode($response,JSON_UNESCAPED_UNICODE);die;
            }
        return $next($request);


    }


}

