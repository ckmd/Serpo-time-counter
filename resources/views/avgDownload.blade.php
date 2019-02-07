<?php
include_once("../xlsxwriter.class.php");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

$filename = $nameFile.".xlsx";
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$header = array(
    'basecamp'=>'string',
    'serpo'=>'string',
    'durasi_sbu'=>'0.00',
    'prep_time'=>'0.00',
    'travel_time'=>'0.00',
    'work_time'=>'0.00',
    'complete_time'=>'0.00',
    'rsps'=>'0%',
);

$rows = array();
		foreach($dbAvgExcel as $d) {
            $rows[] = array(
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