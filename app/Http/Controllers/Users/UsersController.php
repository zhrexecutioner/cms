<?php

namespace App\Http\Controllers\users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;

class UsersController extends Controller
{
    public function login()
    {
    	return view('users.log');
    }
    public function dologin(Request $request)
    {
    	$username=$request->input('username');
    	$password=$request->input('password');
        if(empty($username)){
            echo '用户名必填！';exit;
        }
        if(empty($password)){
            echo '密码必填！';exit;
        }
    	$u = UserModel::where(['nick_name'=>$username])->first();
    	if($u){
    		$aaa = UserModel::where(['nick_name'=>$username])->first()->toarray();
    		$bbb=$aaa['pass'];
    		//var_dump($bbb);exit;
    		//$res = UserModel::where(['pass'=>$password])->first();
    		if($bbb==$password){
    			echo '登陆成功！';exit;
    		}else{
    			echo '密码有误！';exit;
    		}
    	}else{
    		echo '不存在该用户！';exit;
    	}
    }
    public function update()
    {
        return view('users.update');
    }
    public function doupdate(Request $request)
    {
        $username=$request->input('username');
        $passworda=$request->input('passworda');
        $password=$request->input('password');
        if(empty($username)){
            echo '用户名必填！';exit;
        }
        if(empty($passworda)){
            echo '原密码必填！';exit;
        }
        if(empty($password)){
            echo '新密码必填！';exit;
        }
        $u = UserModel::where(['nick_name'=>$username])->first();
        if($u){
            $aaa = UserModel::where(['nick_name'=>$username])->first()->toarray();
            $bbb=$aaa['pass'];
            //var_dump($bbb);exit;
            //$res = UserModel::where(['pass'=>$password])->first();
            if($bbb==$passworda){
                $date=[
                'pass'=>$password
                ];
                $aaa = UserModel::where(['nick_name'=>$username])->update($date);
                
                //var_dump($bbb);exit;
                //$res = UserModel::where(['pass'=>$password])->first();
                if($aaa){
                    echo '修改成功！';exit;
                }else{
                    echo '修改失败！';exit;
                }
            }else{
                echo '原密码有误！';exit;
            }
        }else{
            echo '不存在该用户！';exit;
        }
    }
}
