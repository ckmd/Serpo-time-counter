<?php
include_once("../xlsxwriter.class.php");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

$filename = "All Data.xlsx";
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$header = array(
    'AR_id'=>'0',
    'Prob_id'=>'0',
    'Kode_wo'=>'0',
    'Region'=>'string',
    'Basecamp'=>'string',
    'Serpo'=>'string',
    'Durasi_SBU'=>'0.00',
    'Preparation'=>'0.00',
    'Travelling'=>'0.00',
    'Working'=>'0.00',
    'RSPS'=>'0%',
    'gangguan'=>'string',
    'kendala'=>'string',
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
            "$d->rsps",
            "$d->root_cause",
            "$d->kendala"
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