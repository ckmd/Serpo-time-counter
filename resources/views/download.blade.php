<?php
include_once("../xlsxwriter.class.php");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

$filename = "document.xlsx";
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$header = array(
    'ar_id'=>'string',
    'prob_id'=>'string',
    'kode_wo'=>'number',
    'region'=>'string',
    'basecamp'=>'string',
    'serpo'=>'string',
    'durasi_sbu'=>'number',
    'prep_time'=>'number',
    'travel_time'=>'number',
    'work_time'=>'number',
    'complete_time'=>'number',
    'rsps'=>'0%',
);

$rows = array();
		foreach($datas as $d) {
            $rows[] = array(
            "$d->ar_id",
            "$d->prob_id",
            "$d->kode_wo",
            "$d->region",
            "$d->basecamp",
            "$d->serpo",
            "$d->durasi_sbu",
            "$d->prep_time",
            "$d->travel_time",
            "$d->work_time",
            "$d->complete_time",
            "$d->rsps"
        );
		}
            $writer = new XLSXWriter();
$writer->setAuthor('icon+'); 
$writer->writeSheetHeader('Sheet1', $header);
foreach($rows as $row)
	$writer->writeSheetRow('Sheet1', $row);
$writer->writeToStdOut();
//$writer->writeToFile('example.xlsx');
//echo $writer->writeToString();
exit(0);