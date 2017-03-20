<?php
include_once("xlsxwriter.class.php");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

require_once __DIR__."/../../vendor/autoload.php";
require '../../config/db.php';  // for connection to database
require '../../config/config.php';
$con = new Connection;
$db = $con->mongo;
$config = new Config;
$collection = "Costing.details";

$filename = "Report.xlsx";
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$id = $_GET['id'];
// $id = '586b349a2514972b88005a4b';
$filter["_id"] = new MongoDB\BSON\ObjectId($id);
// $filter["\$text"] = (object)array("\$search"=>"asdf");
$res = $db->$collection->find($filter, [
			'projection' => []
		]);
foreach($res as $k => $r){
	$a = (string)$r->_id; // convert object id to string
	$r = json_decode(json_encode($r), true);
	$r['_id'] = $a;
	$result[$k] = $r;
}
if($result[0]['buyer'] == 'domestic'){
    $buyer = $result[0]['domestic'];
    $division = $result[0]['ddivision'];
    $brand = $result[0]['dbrand'];
}
if($result[0]['buyer'] == 'export'){
    $buyer = $result[0]['export'];
    $division = $result[0]['xdivision'];
    $brand = $result[0]['xbrand'];
}

$writer = new XLSXWriter();
$writer->setAuthor('Pooja Clothing');
// $header = array(
//     'year'=>'string',
//     'month'=>'string',
//     'amount'=>'money',
//     'first_event'=>'datetime',
//     'second_event'=>'date',
// );
// $data1 = array(
//     array('Style #',$result[0]['style'],'Order Qty',$result[0]['qty']),
//     array('Color',$result[0]['color'], 'Delivery Date',$result[0]['deldate']),
//     array('Buyer',$buyer),
//     array('Division',$division),
//     array('Brand',$brand),
//     array('Season',$result[0]['season']),
//     array('Garment Desc'),
// );
$writer->writeSheetRow('Sheet1',array('Style #',$result[0]['style'],'Order Qty',$result[0]['qty']));
$writer->writeSheetRow('Sheet1',array('Color',$result[0]['color'], 'Delivery Date',date('d/m/Y',strtotime($result[0]['deldate']))));
$writer->writeSheetRow('Sheet1',array('Buyer',$buyer));
$writer->writeSheetRow('Sheet1',array('Division',$division));
$writer->writeSheetRow('Sheet1',array('Brand',$brand));
$writer->writeSheetRow('Sheet1',array('Season',$result[0]['season']));
$writer->writeSheetRow('Sheet1',array('Garment Desc'));
$writer->writeSheetRow('Sheet1',array('ITEM','DESC/SIZE/COL/WIDTH/PLACEMENT/REMARK','SUPPLIER','Rate','Unit','F Cons.','Unit','Ttl Amt / Pc'));
$writer->writeSheetRow('Sheet1',array('FABRIC'));
foreach ($result[0]['items']['fabric'] as $key => $value) {
    $writer->writeSheetRow('Sheet1',array($value['item'],'',$value['supplier'],(float)$value['rate2'],$value['unit2'],(float)$value['fconsume2'],$value['unit1'],(float)$value['amount2']));
}
foreach ($result[0]['items2']['fabric2'] as $key => $value) {
    $writer->writeSheetRow('Sheet1',array($value['item'],'',$value['supplier'],(float)$value['rate2'],$value['unit2'],(float)$value['fconsume2'],$value['unit1'],(float)$value['amount2']));
}
$writer->writeSheetRow('Sheet1',array('Accessories'));
foreach ($result[0]['items']['accessories'] as $key => $value) {
    $writer->writeSheetRow('Sheet1',array($value['item'],'',$value['supplier'],(float)$value['rate2'],$value['unit2'],(float)$value['fconsume2'],$value['unit1'],(float)$value['amount2']));
}
foreach ($result[0]['items2']['accessories2'] as $key => $value) {
    $writer->writeSheetRow('Sheet1',array($value['item'],'',$value['supplier'],(float)$value['rate2'],$value['unit2'],(float)$value['fconsume2'],$value['unit1'],(float)$value['amount2']));
}
$writer->writeSheetRow('Sheet1',array('Process - Manufacturing'));
foreach ($result[0]['items']['process'] as $key => $value) {
    $writer->writeSheetRow('Sheet1',array($value['item'],'',$value['supplier'],(float)$value['rate2'],$value['unit2'],(float)$value['fconsume2'],$value['unit1'],(float)$value['amount2']));
}
foreach ($result[0]['items2']['process2'] as $key => $value) {
    $writer->writeSheetRow('Sheet1',array($value['item'],'',$value['supplier'],(float)$value['rate2'],$value['unit2'],(float)$value['fconsume2'],$value['unit1'],(float)$value['amount2']));
}
$writer->writeSheetRow('Sheet1',array('Other Expenses'));
foreach ($result[0]['expense'] as $key => $value) {
    $writer->writeSheetRow('Sheet1',array($value['item'],'','','','','','',(float)$value['amount2']));
}
$writer->writeSheetRow('Sheet1',array('Cost','','','','','','',(float)$result[0]['cost']['cost2']));
$writer->writeSheetRow('Sheet1',array('','Add Rejection','','','',(float)$result[0]['total']['rrate2'],'',(float)$result[0]['cost']['rej2']));
$writer->writeSheetRow('Sheet1',array('','Total','','','','','',(float)$result[0]['cost']['total2']));
$writer->writeSheetRow('Sheet1',array('','Add Overheads','','','',(float)$result[0]['total']['orate2'],'',(float)$result[0]['cost']['ovh2']));
$writer->writeSheetRow('Sheet1',array('','Total Cost','','','','','',(float)$result[0]['cost']['tcost2']));
$writer->writeSheetRow('Sheet1',array('','Add Commission','','','',(float)$result[0]['total']['crate2'],'',(float)$result[0]['cost']['com2']));
$writer->writeSheetRow('Sheet1',array('','FOB/C&F/CIF (INR)','','','','','',(float)$result[0]['cost']['del2']));
$writer->writeSheetRow('Sheet1',array('','Exchange Rate','','','','','',(float)$result[0]['cost']['exr2']));
$writer->writeSheetRow('Sheet1',array('','FOB/C&F/CIF (Currency)','','','','',(float)$result[0]['currency'],(float)$result[0]['cost']['delc2']));
$writer->writeSheetRow('Sheet1',array(''));
$writer->writeSheetRow('Sheet1',array('','','','','','Quoted','',(float)$result[0]['cost']['quoted']));
$writer->writeSheetRow('Sheet1',array(''));
$writer->writeSheetRow('Sheet1',array('Remark',$result[0]['remark']));
$data2 = array(
    array('Buyer',$buyer),
    array('Division',$division),
    array('Brand',$brand),
    array('Season',$result[0]['season']),
    array('Style','Color','Order Qty','Garment Desc','Picture','Delivery Date','Delivery','Currency','Price','Closed Price','Remark'),
    array($result[0]['style'],$result[0]['color'],$result[0]['qty'],'','',$result[0]['deldate'],$result[0]['delivery'],$result[0]['currency'],$result[0]['cost']['quoted'],$result[0]['cost']['closed'],$result[0]['remark']),
);

// $writer->writeSheet($data1,'Sheet1',$header);
// $writer->writeSheet($data1,'Sheet1');
$writer->writeSheet($data2,'Sheet2');
$writer->writeToStdOut();
//$writer->writeToFile('example.xlsx');
//echo $writer->writeToString();
exit(0);


