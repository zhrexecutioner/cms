<?php

namespace App\Http\Controllers\Xcx;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Model\XcxModel;
use App\Model\IndexfuwuModel;
use App\Model\IndexworkModel;
use App\Model\SearchModel;

class XcxController extends Controller
{
    public function indexlunbo(){
    	$arrImgs = array(
    		'https://images.unsplash.com/photo-1551334787-21e6bd3ab135?w=640',
      		'https://images.unsplash.com/photo-1551214012-84f95e060dee?w=640',
      		'https://images.unsplash.com/photo-1551446591-142875a901a1?w=640'
    	);
    	echo json_encode($arrImgs);
    }

    public function indexgg(){
    	$arrImgs = [
    		[
    			"image"=>'/images/hd1.png',
    		],
    		[
    			"image"=>'/images/hd2.png',
    		]

    	];
    	echo json_encode($arrImgs);
    }

    public function indextitle(){
    	$arrImgs = array(
    		"title"=>'双11返场！！'
    	);
    	echo json_encode($arrImgs);
    }
    public function indexwork(){
    	$first = IndexworkModel::select('url','images','content')->get()->toArray();
    	echo json_encode($first);
    }

    public function indexfuwu(){
    	$first = IndexfuwuModel::select('url','images','content')->get()->toArray();
    	echo json_encode($first);
    }


    public function car_source(){
    	$first = XcxModel::select('name','image_src')->limit(10)->get()->toArray();
    	echo json_encode($first);
    }

    public function car_sourcejk(){
    	$arr=[];
    	$k=0;
    	for($i=ord("a");$i <= ord("z");$i++){
    		$first = XcxModel::where('brand_first',chr($i))->select('name','image_src')->get()->toArray();
    		if(empty($first)){
    			$arr[$k]['alpha']="";
				$arr[$k]['list']="";
    		}else{
    			$arr[$k]['alpha']=chr($i);
				$arr[$k]['list']=$first;
    		}
			$k=$k+1;
		}
		echo json_encode($arr);
    }
    public function search(){
    	$first=SearchModel::get()->toArray();
    	//var_dump($first);exit;
    	echo json_encode($first);
    }

    public function formSubmit(Request $request){
    	$name=$request->input('name');
    	$pwd=$request->input('pwd');
    	$data=[
    		'name'=>$name,
    		'pwd'=>$pwd
    	];
    	return $data;
    }
 }