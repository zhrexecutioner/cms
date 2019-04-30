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
            Redis::expire($redis_key, $newfangshuatime);
            # 一分钟 20 次   上限
            if ($num > $newfangshuanum) {
                # 防刷
                $response = [
                    'errCode' => 45019
                    , 'errMsg' => '访问过于频繁'
                    , 'num' => $num
                ];


                # 10分钟
                Redis::expire($redis_key, $newfangshuatime);
                echo json_encode($response,JSON_UNESCAPED_UNICODE);die;
            }
        return $next($request);


    }


}

