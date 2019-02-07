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
    'Basecamp'=>'string',
    'Serpo'=>'string',
    'Jumlah WO'=>'number',
    'Durasi SBU'=>'0.00',
    'Preparation Time'=>'0.00',
    'Travel Time'=>'0.00',
    'Work Time'=>'0.00',
    'Complete Time'=>'0.00',
    'RSPS'=>'0%',
);

$rows = array();
		foreach($dbAvgExcel as $d) {
            $rows[] = array(
            "$d->basecamp",
            "$d->serpo",
            "$d->jumlah_wo",
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