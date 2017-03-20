<?php
ob_start();
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: X-Pre-Hash");
header("Access-Control-Allow-Headers: X-Hash2, X-Custom-Auth");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
date_default_timezone_set('UTC');
// import all required classes
require_once __DIR__ . "/vendor/autoload.php";
require './config/db.php';  // for connection to database
require './config/config.php';
require './options/options.php'; // filter options for get request
require './options/hasher.php';

// Class Instances
$con = new Connection;
$db = $con->mongo;
//$gridfs = $db->getGridFS();
$config = new Config;
$option = new Options;
$hasher = new hashGenerator;

//print_r($gridfs->find());
// echo $hasher->randomHash();

$result = [];

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':

		//validate();
		$getdata = urlData($config);

		$projection = [];
		// Filters for data
		if(isset($_GET['filters'])) {
			$filters = $_GET['filters'];
			$filter = $option->filter($filters);
		} else {$filter = [];}

		// Sorting data
		if(isset($_GET['sort'])) {
			$sort = $_GET['sort'];
			$sort = $option->sort($sort);
		} else {$sort = [];}

		// Select column
		if(isset($_GET['col'])) {
			$projection = [$_GET['col'] => 1];
		} else {$projection = [];}

		$collection = $getdata[0];
		if(isset($getdata[1])) {
			$filter["_id"] = new MongoDB\BSON\ObjectId($getdata[1]);
		}

		if($collection == "scripts"){
			$file = "scripts/".$_GET['module'].".js";
			if(file_exists($file)){
				die(file_get_contents($file));
			}
		}

		$options = [
			'projection' => $projection
		];

		// count data
		if(isset($_GET['count'])) {
			if(isset($_GET['search'])){
				if($_GET['search'] != ''){
					$asd["\$text"] = (object)array("\$search"=>$_GET['search']);
					$res = $db->$collection->count($asd,$options);
					print_r($res);
					die();
				} else {
					$res = $db->$collection->count();
					print_r($res);
					die();
				}
			} else {
				$res = $db->$collection->count();
				print_r($res);
				die();
			}
		}

		// limit data
		if(isset($_GET['limit'])) {
			$options['limit'] = (int)$_GET['limit'];
		}

		// skip data
		if(isset($_GET['skip'])) {
			$options['skip'] = (int)$_GET['skip'];
		}

		// sort data
		if(isset($_GET['sort'])) {
			$options['sort'] = $sort;
		}

		//search data
		if(isset($_GET['search'])){
			if($_GET['search'] != ''){
				$filter["\$text"] = (object)array("\$search"=>$_GET['search']);
			}
		}

		$res = $db->$collection->find($filter, $options);

		foreach($res as $k => $r){
			$a = (string)$r->_id; // convert object id to string
			$r = json_decode(json_encode($r), true);
			$r['_id'] = $a;
			$result[$k] = $r;
		}
		// print_r($res);
		break;
	case 'POST':

		// validate();
		$getdata = urlData($config);
		$collection = $getdata[0];
		if($collection == "users.login") {
			if(isset($_POST['userpass'])) {
				$data = explode(':',$_POST['userpass']);
				$filter = array('user'=>$data[1], 'pwd' => $data[2]);
				$res = $db->$collection->find($filter);
				foreach($res as $k => $r){
					$a = (string)$r->_id; // convert object id to string
					$r = json_decode(json_encode($r), true);
					$r['_id'] = $a;
					$result[$k] = $r;
				}
				if($result == []) { die('{"error":"Invalid Credentials"}'); }
				if($result[0]['active'] == 0) { die('{"error":"Invalid Credentials"}'); }
				if(md5($data[0].":".$result[0]['user'].":".$result[0]['pwd']) == $_SERVER['HTTP_X_HASH2']) {
					unset($result[0]['pwd']);
					if(session($result[0]['_id'], $_SERVER['HTTP_X_HASH2'])) {
						print_r(json_encode($result[0]));
					} else {
						die('{"error":"Unknown Error Occured"}');
					}
				} else {
					die('{"error":"Invalid Credentials"}');
				}
				die();
			}
		}
		//validate();
		$data = "";
		// get post data (json format required)
		if(isset($_POST['data'])) {
			$data = $_POST['data'];
			$data = json_decode($data, true);
		}
		if($collection == "scripts"){
			$file = "scripts/".$data[0]['module'].".js";
			if(file_exists($file)){
				if (unlink($file)){
					file_put_contents($file, $data[0]['content']);
				}
			} else {
				file_put_contents($file, $data[0]['content']);
			}
			die('{"success":"Script Saved"}');
		}
		$created = date("d M, Y H:i:s P");
		foreach($data as $key => $value){
			$value['timestamp']['createdAt'] = $created;
			$data[$key] = $value;
		}

		$res = $db->$collection->insertMany($data);
		$result = $res->getInsertedIds();
		array_walk($result,'getId');
		$result = array("success" => $result);
		break;
	case 'PUT':

		//validate();
		$getdata = urlData($config);
		$type = $getdata[0];
		$collection = $getdata[1];
		if(isset($getdata[2])) {
			$id = new MongoDB\BSON\ObjectId($getdata[2]);
		} else {
			die('{"error":"incorrect input"}');
		}
		$data = "";
		// get post data (json format required)
		if(!empty(file_get_contents('php://input'))) {
			$data = str_replace("data=","",file_get_contents('php://input'));
			$data = json_decode(urldecode($data), true)[0];
		}
		$updated = date("d M, Y H:i:s P");

		if($type === "push") {
			$resa['timestamp.updatedAt'] = $updated;
			$res = $db->$collection->updateOne(["_id" => $id], ['$push' => $data, '$set' => $resa]);
		}
		$data['timestamp.updatedAt'] = $updated;
		$res = $db->$collection->updateOne(["_id" => $id], ['$set' => $data]);
		$result = $res->getModifiedCount();
		$result = array("success" => "Modified ".$result." record Succesfully");
		break;
	case 'DELETE':

		//validate();
		$getdata = urlData($config);
		$collection = $getdata[0];
		if(isset($getdata[1])) {
			$id = new MongoDB\BSON\ObjectId($getdata[1]);
		} else {
			die('{"error":"incorrect input"}');
		}
		if($collection == "modules.scripts"){
			$filter["_id"] = new MongoDB\BSON\ObjectId($getdata[1]);
			$cursor = $db->$collection->find($filter, ['projection'=>['module'=>1]]);
			foreach ($cursor as $val) {
				$module = $val['module'];
			}
			$file = "scripts/".$module.".js";
			unlink($file);
		}
		$res = $db->$collection->deleteOne(["_id" => $id]);
		$result = $res->getDeletedCount();
		$result = array("success" => "Deleted ".$result." record Succesfully");
		break;
}

// print_r($result);
print_r(json_encode($result));

// get data from url
function urlData($config) {
	$uri = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$getdata = str_replace($config->BASE_URL."api.php","","http://".$uri);
	if (strpos($getdata, '?') !== false) {
		$getdata = strtok($getdata, '?');
	}
	$getdata = explode('/',$getdata); // convert input to array
	if(!isset($getdata[1])){
		die('{"error":"incorrect input"}');
	}
	array_splice($getdata, 0, 1); // remove first value
	if(isset($getdata[0]) && $getdata[0] == ""){ die('{"error":"incorrect input"}'); } //check for collection

	return $getdata;
}

// convert objectId to string
function getId(&$value, $key){
	$value = (string) new MongoDB\BSON\ObjectId($value);
}

function validate() {
	$con = new Connection;
	$db = $con->mongo;
	$collection = "users.session";
	if(isset($_SERVER['HTTP_X_CUSTOM_AUTH'])) {
		$data = explode(':',$_SERVER['HTTP_X_CUSTOM_AUTH']);
		$filter = array('uid'=>$data[0], 'sessionId' => $data[1]);
		$res = $db->$collection->find($filter);
		foreach($res as $k => $r){
			$a = (string)$r->_id; // convert object id to string
			$r = json_decode(json_encode($r), true);
			$r['_id'] = $a;
			$result[$k] = $r;
		}
		if($result == []) { die('{"error":"Unauthorized Request"}'); }
	} else {
		$hash = new hashGenerator;
		$prehash = $hash->randomHash();
		header("X-Pre-Hash: ".$prehash."");
		die('{"error":"Unauthorized Request"}');
	}

}

function session($uid, $sessionId) {
	$con = new Connection;
	$db = $con->mongo;
	$table = "users.session";
	$updateResult = $db->$table->updateMany(
		['uid' => $uid],
		['$set' => ['active' => false]]
	);
	$r = $db->$table->insertOne([
		'uid' => $uid,
		'sessionId' => $sessionId,
		'active' => true,
		'timestamp' => [
			'createdAt' => date("d M, Y H:i:s P")
		]
	]);
	$count = $r->getInsertedCount();
	if ($count == 1) {
		return true;
	} else {
		return false;
	}
}

?>
