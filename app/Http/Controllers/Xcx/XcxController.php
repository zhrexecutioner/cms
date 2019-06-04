<?php

namespace App\Http\Controllers\Xcx;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
}
