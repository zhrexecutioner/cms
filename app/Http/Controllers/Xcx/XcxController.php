<?php

namespace App\Http\Controllers\Xcx;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Model\XcxModel;

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
    	$arr=[
    		[
    			"url"=>"/pages/car/car",
    			"images"=>"../../images/desk_publish@2x.png",
    			"content"=>"发布车源"
    		],
    		[
    			"url"=>"/pages/car/car",
    			"images"=>"../../images/desk_publish@2x.png",
    			"content"=>"发布车源"
    		],
    	];
    	echo json_encode($arr);
    }
    public function indexfuwu(){
    	$arr=[
    		[
    			"url"=>"/pages/car/car",
    			"images"=>"../../images/home_car_source@2x.png",
    			"content"=>"车源管理"
    		],
    		[
    			"url"=>"/pages/car/car",
    			"images"=>"../../images/home_car_source@2x.png",
    			"content"=>"车源管理"
    		],
    	];
    	echo json_encode($arr);
    }

    public function car_source(){
    	$arr=[
    		[
        		"image_src"=>'/images/w640_h358_9c1387930fa14ba0a30716865d0433b3.jpeg',
        		"name"=>'奥迪'
    		],
    
    	];
    	var_dump($arr);exit;
    	echo json_encode($arr);
    }

    // public function car_source(){
    // 	$first = XcxModel::select('name','image_src')->get()->toArray();
    // 	$arr[]=$first;
    // 	var_dump($arr);exit;
    // 	echo json_encode($arr);
    // }

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

    // public function car_sourcejk(){
    // 	$arr=[
    // 		[
    // 			"alpha"=>'bbb',
    // 			"list"=>[
    // 				[
    // 					"image_src"=>'',
    // 					"name"=>'nnn'
    // 				],
    // 				[
    // 					"image_src"=>'',
    // 					"name"=>'kkk'
    // 				]
    // 			]
    // 		],
    // 		[
    // 			"alpha"=>'bbb',
    // 			"list"=>[
    // 				[
    // 					"image_src"=>'',
    // 					"name"=>'nnn'
    // 				],
    // 				[
    // 					"image_src"=>'',
    // 					"name"=>'kkk'
    // 				]
    // 			]
    // 		]
    // 	];
    // 	var_dump($arr);
    // }
 }

// commonCar:[
//       {
//         alpha:'bbb',
//         list:[
//           {
//             image_src:'',
//             name:'nnn'
//           },
//           {
//             image_src: '',
//             name: 'kkk'
//           }
//         ],
//       },
//       {
//         alpha: 'mmm',
//         list: [
//           {
//             image_src: '',
//             name: 'nnn'
//           },
//           {
//             image_src: '',
//             name: 'kkk'
//           }
//         ],
//       }
//     ],
