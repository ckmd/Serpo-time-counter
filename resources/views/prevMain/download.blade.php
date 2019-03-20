<?php
include_once("../xlsxwriter.class.php");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

$filename = "Report PM.xlsx";
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$header = array(
    'No'=>'0',
    'Region'=>'string', 
    'Total POP Asset'=>'0',
    'Total PM POP'=>'0',
    'Ratio Total'=>'0.00%',
    'Asset POP D'=>'0',
    'PM POP D'=>'0',
    'Ratio POP D'=>'0.00%',
    'Asset POP B'=>'0',
    'PM POP B'=>'0',
    'Ratio POP B'=>'0.00%',
    'Asset POP SB'=>'0',
    'PM POP SB'=>'0',
    'Ratio POP SB'=>'0.00%',
    'PM FOC'=>'0',
    'PM lain'=>'0',
);

$rows = array();
$i = 1;
		foreach($datas as $d) {
            $rows[] = array(
            "$i",
            "$d->region",
            "$d->total_POP_asset",
            "$d->total_PM_POP",
            "$d->ratio_total",
            "$d->asset_POP_D",
            "$d->PM_POP_D",
            "$d->ratio_POP_D",
            "$d->asset_POP_B",
            "$d->PM_POP_B",
            "$d->ratio_POP_B",
            "$d->asset_POP_SB",
            "$d->PM_POP_SB",
            "$d->ratio_POP_SB",
            "$d->PM_FOC",
            "$d->PM_lain"
            );
            $i++;
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