@extends ('layouts.master')

@section('content')
<!-- Upload Files Start Here -->
<form action="/excel" method="POST" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="file" name="excelFile">
    <input type="submit" value="submit">
</form>
<!-- Upload Files Ends Here -->
<!-- PHP for uploading Files -->
<?php
    $getSheet = null;
    $highestRow = null;
    require_once '../classes/PHPExcel/IOFactory.php';
    if(isset($_FILES['excelFile']) && !empty($_FILES['excelFile']['tmp_name']))
    {
        $excelObject = PHPExcel_IOFactory::load($_FILES['excelFile']['tmp_name']);
        $getSheet = $excelObject->getActiveSheet()->toArray(null);
        $highestRow = $excelObject->setActiveSheetIndex(0)->getHighestDataRow();
    // echo '<pre>';
    // echo var_dump($getSheet);
?>
<div style="text-align:center;">
    <h2><?php echo $_FILES['excelFile']['name'];?></h2>
</div>
<!-- Tables Start Here -->
<table class="table table-responsive">
    <thead>
        <tr>
            <th>No</th>
            <th><?php echo $getSheet[0][0];?></th>
            <th><?php echo $getSheet[0][1];?></th>
            <th><?php echo $getSheet[0][2];?></th>
            <th><?php echo $getSheet[0][5];?></th>
            <th><?php echo $getSheet[0][6];?></th>
            <th><?php echo $getSheet[0][7];?></th>
            <th>Durasi SBU</th>
            <th>Preparation Time</th>
            <th>Travel Time</th>
            <th>Work Time</th>
            <th>Complete Time</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 1; $i < $highestRow; $i++) { 
            if ($getSheet[$i][0] != '') {
        ?>
        <tr>
            <td><?php echo $i;?></td>
            <td><?php echo $getSheet[$i][0];?></td>
            <td><?php echo $getSheet[$i][1];?></td>
            <td><?php echo $getSheet[$i][2];?></td>
            <td><?php echo $getSheet[$i][5];?></td>
            <td><?php echo $getSheet[$i][6];?></td>
            <td><?php echo $getSheet[$i][7];?></td>
            <!-- Menghitung Durasi SBU -->
            <!-- Selisih Antara AR_Date dengan WO Date -->
            <?php
                $SBU = null;
                $AR_Date = new DateTime($getSheet[$i][8]);
                $WO_Date = DateTime::createFromFormat('d M Y H:i:s',$getSheet[$i][9]);
                $SBU = date_diff($WO_Date, $AR_Date);
            ?>
            <!-- Filtering Durasi SBU Start Here -->
            <?php
            if($SBU->d == 0 && $SBU->h == 0 && $SBU->i == 0 && $SBU->s == 0){
                echo '
                <td>null</td>';
            }
            else if($SBU->i == 0 && $SBU->h == 0 && $SBU->d == 0){
                echo '
                <td>'.$SBU->format('%s s').'</td>';
            } else if($SBU->h == 0 && $SBU->d == 0){
                echo '
                <td>'.$SBU->format('%i m, %s s').'</td>';
            } else if($SBU->d == 0){
                echo '
                <td>'.$SBU->format('%h h, %i m, %s s').'</td>';
            } else {
                echo '
                <td>'.$SBU->format('%d d, %h h, %i m, %s s').'</td>';                            
            }
            ?>
            <!-- Menghitung Durasi Preparation -->
            <!-- Selisih Antara WO Date dengan Start Driving -->
            <?php
                $preparation = null;
                $start_driving = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][11]),0,19));
                if($getSheet[$i][11]=='' || $getSheet[$i][9]==''){
                    $preparation = date_diff($WO_Date, $WO_Date);
                }else{
                    $preparation = date_diff($start_driving, $WO_Date);
                }
            ?>
            <!-- Filtering Durasi Preparation Start Here -->
            <?php
            if($preparation->d == 0 && $preparation->h == 0 && $preparation->i == 0 && $preparation->s == 0){
                echo '
                <td></td>';
            }
            else if($preparation->i == 0 && $preparation->h == 0 && $preparation->d == 0){
                echo '
                <td>'.$preparation->format('%s s').'</td>';
            } else if($preparation->h == 0 && $preparation->d == 0){
                echo '
                <td>'.$preparation->format('%i m, %s s').'</td>';
            } else if($preparation->d == 0){
                echo '
                <td>'.$preparation->format('%h h, %i m, %s s').'</td>';
            } else {
                echo '
                <td>'.$preparation->format('%d d, %h h, %i m, %s s').'</td>';                            
            }
            ?>
            <!-- Menghitung Durasi Travel Time -->
            <!-- Selisih Antara Start Travel dengan Start Work -->
            <?php
                $travel = null;
                $start_working = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][12]),0,19));
                if($getSheet[$i][12]=='' || $getSheet[$i][11]==''){
                    $travel = date_diff($start_driving, $start_driving);
                }else{
                    $travel = date_diff($start_working, $start_driving);
                }
            ?>
            <!-- Filtering Durasi Travel Time Start Here -->
            <?php
            if($travel->d == 0 && $travel->h == 0 && $travel->i == 0 && $travel->s == 0){
                echo '
                <td></td>';
            }
            else if($travel->i == 0 && $travel->h == 0 && $travel->d == 0){
                echo '
                <td>'.$travel->format('%s s').'</td>';
            } else if($travel->h == 0 && $travel->d == 0){
                echo '
                <td>'.$travel->format('%i m, %s s').'</td>';
            } else if($travel->d == 0){
                echo '
                <td>'.$travel->format('%h h, %i m, %s s').'</td>';
            } else {
                echo '
                <td>'.$travel->format('%d d, %h h, %i m, %s s').'</td>';                            
            }
            ?>
            <!-- Menghitung Durasi Work Time -->
            <!-- Selisih Antara Start Work dengan Request Complete -->
            <?php
                $working = null;
                $req_complete = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][15]),0,19));
                if($getSheet[$i][15]=='' || $getSheet[$i][12]==''){
                    $working = date_diff($start_working, $start_working);
                }else{
                    $working = date_diff($req_complete, $start_working);
                }
            ?>
            <!-- Filtering Durasi Work Time Start Here -->
            <?php
            if($working->d == 0 && $working->h == 0 && $working->i == 0 && $working->s == 0){
                echo '
                <td></td>';
            }
            else if($working->i == 0 && $working->h == 0 && $working->d == 0){
                echo '
                <td>'.$working->format('%s s').'</td>';
            } else if($working->h == 0 && $working->d == 0){
                echo '
                <td>'.$working->format('%i m, %s s').'</td>';
            } else if($working->d == 0){
                echo '
                <td>'.$working->format('%h h, %i m, %s s').'</td>';
            } else {
                echo '
                <td>'.$working->format('%d d, %h h, %i m, %s s').'</td>';                            
            }
            ?>
            <!-- Menghitung Durasi Reuest Complete Time -->
            <!-- Selisih Antara Request Complete dengan Complete -->
            <?php
                $complete_time = null;
                $complete = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][16]),0,19));
                if($getSheet[$i][16]=='' || $getSheet[$i][15]==''){
                    $complete_time = date_diff($req_complete, $req_complete);
                }else{
                    $complete_time = date_diff($complete, $req_complete);
                }
            ?>
            <!-- Filtering Durasi Work Time Start Here -->
            <?php
            if($complete_time->d == 0 && $complete_time->h == 0 && $complete_time->i == 0 && $complete_time->s == 0){
                echo '
                <td></td>';
            }
            else if($complete_time->i == 0 && $complete_time->h == 0 && $complete_time->d == 0){
                echo '
                <td>'.$complete_time->format('%s s').'</td>';
            } else if($complete_time->h == 0 && $complete_time->d == 0){
                echo '
                <td>'.$complete_time->format('%i m, %s s').'</td>';
            } else if($complete_time->d == 0){
                echo '
                <td>'.$complete_time->format('%h h, %i m, %s s').'</td>';
            } else {
                echo '
                <td>'.$complete_time->format('%d d, %h h, %i m, %s s').'</td>';                            
            }
            ?>
            <!-- Menghitung Semua End Here -->
        </tr>
        <?php
            }
        }?>
    </tbody>
</table>

<?php
}
?>
<!-- Tables End Here -->
@endsection