<?php
header("Content-type: text/html; charset=gb2312");
include "api.inc.php";
$ID=17;//API id
$key="---------";

//先判断通讯
if(!is_array($_GET) || count($_GET)<=0){//先判断是否通过get传值了
	header('HTTP/1.1 404 Not Found');
	exit;
}

if($_GET["type"]=="test"){//通信测试
	echo "success";
	exit;
}

$api = new Licence_API($ID, $key);


if($_GET["type"]=="licence_detail"){//授权查询 两个options 可以二选一
	$detail_act['act'] = "licence_detail";
	$detail_act['options']['LicenceID'] = $_GET["LicenceID"];
	$detail_act['options']['QQ'] = $_GET["QQ"];

	$api_return = $api->act($detail_act);

	//print_r($api_return);.
	echo json_encode($api_return);
	exit;
}

if($_GET["type"]=="licence_add"){//授权添加

	$add_act['act'] = "licence_add";
	if($_GET["Preview"]=="true"){
		$add_act['options']['Preview'] = true;//预览价格
	}else{
		$add_act['options']['Preview'] = false;//提交订单
	}

	$add_act['options']['QQ'] = $_GET["QQ"];//  分割\r\n

	//$add_act['options']['QQ']可以是字符串也可以是数组
	//例如
	//$add_act['options']['QQ'] = array("724583668");
	//多个qq例子
	//$add_act['options']['QQ'] = "724583668\r\nGGPSB"
	//$add_act['options']['QQ'] = array("724583668", "GGPSB");

	$add_act['options']['Type'] = "Vip";
	$add_act['options']['Email'] = $_GET["Email"];
	$add_act['options']['ServiceQQ'] = $_GET["ServiceQQ"];
	$add_act['options']['Taobao'] = "10000";
	$add_act['options']['Service'] = "10000";
	$add_act['options']['Note'] = $_GET["Note"];
	$add_act['options']['Date']['Year'] = $_GET["Year"];
	$add_act['options']['Date']['Month'] = $_GET["Month"];
	$add_act['options']['Date']['Day'] = $_GET["Day"];
	
	$api_return = $api->act($add_act);
	
	//print_r($api_return);.
	echo json_encode($api_return);
	exit;
}

if($_GET["type"]=="licence_transfer"){//提交过户申请
	$transfer_act['act'] = "licence_transfer";
	$transfer_act['options']['LicenceID'] = $_GET["LicenceID"];
	$transfer_act['options']['Note'] = $_GET["Note"];

	$api_return = $api->act($transfer_act);

	//print_r($api_return);.
	echo json_encode($api_return);
exit;
}

if($_GET["type"]=="order_detail"){//订单详情
	
	$order_detail_act['act'] = "order_detail";
	$order_detail_act['options']['LicenceID'] = $_GET["LicenceID"];
	$order_detail_act['options']['OrderID'] = $_GET["OrderID"];//留空查订单号
	
	$api_return = $api->act($order_detail_act);
	
	//print_r($api_return);.
	echo json_encode($api_return);
	exit;
}
if($_GET["type"]=="licence_edit"){////授权修改
	
	$edit_act['act'] = "licence_edit";
	$edit_act['options']['LicenceID'] = $_GET["LicenceID"];
	if($_GET["Preview"]=="true"){
		$edit_act['options']['Preview'] = true;//预览价格
	}else{
		$edit_act['options']['Preview'] = false;//提交订单
	}
	$edit_act['options']['Date']['Year'] =  $_GET["Year"];
	$edit_act['options']['Date']['Month'] =  $_GET["Month"];
	$edit_act['options']['Date']['Day'] =  $_GET["Day"];
	$edit_act['options']['QQ'] =  $_GET["QQ"];
	$edit_act['options']['Type'] = $_GET["Type"];
	$edit_act['options']['Email'] =  $_GET["Email"];
	$edit_act['options']['ServiceQQ'] =  $_GET["ServiceQQ"];
	$edit_act['options']['Taobao'] =  $_GET["Taobao"];
	$edit_act['options']['Service'] =  $_GET["Service"];
	$edit_act['options']['Note'] =  $_GET["Note"];

	$api_return = $api->act($edit_act);
	
	//print_r($api_return);.
	echo json_encode($api_return);
	exit;
}
if($_GET["type"]=="licence_detail"){//取消订单
	
	$order_refund_act['act'] = "licence_detail";
		if($_GET["Preview"]=="true"){
			$order_refund_act['options']['Preview'] = true;//预览价格
		}else{
			$order_refund_act['options']['Preview'] = false;//提交订单
		}
	
	$order_refund_act['options']['OrderID'] =$_GET["OrderID"];
	
	$api_return = $api->act($order_refund_act);
	
	//print_r($api_return);.
	echo json_encode($api_return);
	exit;
}


