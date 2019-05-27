<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<style type="text/css">

@page { sheet-size: A4-L; }
  
@page bigger { sheet-size: 420mm 370mm; }
  
@page toc { sheet-size: A4; }
  
h1.bigsection {
        page-break-before: always;
        page: bigger;
}

@page rotated { size: landscape; }
.style1 {
	font-family: "TH SarabunPSK";
	font-size: 18pt;
	font-weight: bold;
}
.style2 {
	font-family: "TH SarabunPSK";
	font-size: 16pt;
	font-weight: bold;
}
.style3 {
	font-family: "TH SarabunPSK";
	font-size: 16pt;
	
}
.style5 {cursor: hand; font-weight: normal; color: #000000;}
.style9 {font-family: Tahoma; font-size: 12px; }
.style11 {font-size: 12px}
.style13 {font-size: 9}
.style16 {font-size: 9; font-weight: bold; }
.style17 {font-size: 12px; font-weight: bold; }

</style>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<html>
<head>
    
</head>
<body>
    <?php
    include './components/db.php';
    require_once('./mpdf5.7/mpdf.php'); //ที่อยู่ของไฟล์ mpdf.php ในเครื่องเรานะครับ
    ob_start(); // ทำการเก็บค่า html นะครับ
    ?>
    <?php
    
        $room_type = $_POST['room_type'];
        $month = $_POST['month'];
        $year = $_POST['year'];
                if($room_type == 'หอชาย'){
                $room_type_id = 1; 
                $room_type = "หอพักนักศึกษาชาย";
                }if($room_type == 'หอพักหญิง 1'){
                $room_type_id = 2; 
                $room_type = "หอพักนักศึกษาหญิง 1";
                $room_id = 'f1'; 
                }if($room_type == 'หอพักหญิง 2'){
                $room_type_id = 3; 
                $room_type = "หอพักนักศึกษาหญิง 2";
                $room_id = 'f2'; 
                }
        $sql = "SELECT r.room_id,u.power_charge FROM room AS  r  
        INNER JOIN power_usage AS  u ON r.room_id = u.room_id
        INNER JOIN room_type AS  t ON r.room_type_id = t.room_type_id
        where t.room_type_id = '$room_type_id' and r.room_id LIKE '%$room_id%'
        and u.month = '$month' and u.semester_year = '$year'";
        
        $query = mysqli_query($con,$sql);
        $objResult1 = mysqli_fetch_array($query,MYSQLI_ASSOC);
            ?>
            <?php
                                          
        $strSQL = "SELECT r.room_id,u.power_charge FROM room AS  r  
                INNER JOIN power_usage AS  u ON r.room_id = u.room_id
                INNER JOIN room_type AS  t ON r.room_type_id = t.room_type_id
                where t.room_type_id = '$room_type_id' and r.room_id LIKE '%$room_id%'
                and u.month = '$month' and u.semester_year = '$year' order  by r.room_id ASC ";
        $objQuery = mysqli_query($con,$strSQL);
        ?>
        <center>
<table width="704" border="0" align="center" cellpadding="0" cellspacing="0">
<header>
    <tr>
        <td width="291" align="center"><span class="style2">รายงานสรุปค่าไฟฟ้า (<?php echo $room_type ;?>)</span></td>
    </tr>
    <tr>
        <td height="27" align="center"><span class="style2"> ประจำเดือน <?php echo $month ;?> ประจำปี <?php echo $year ;?>  </span></td>
    </tr>
    <tr>
        <td height="25" align="center"><span class="style2">มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าพระนครเหนือ วิทยาเขตปราจีนบุรี</span></td>
    </tr>
    </header>
</table>
<table width="200" border="0" align="center">
    <thead>
        <tr>
        <td align="center">&nbsp;</td>
        </tr>
    </thead>
</table>
            <table width="80%" border="1" align="center" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                <th rowspan="2" width="100" height="30" align="center" bgcolor="#D5D5D5" > ห้อง</th>
                <th rowspan="2" width="135" height="30" align="center" bgcolor="#D5D5D5"> รหัสนักศึกษา </th>
                <th rowspan="2" width="250" height="30" align="center" bgcolor="#D5D5D5" > ชื่อ-นามสกุล </th>
                <th colspan="3" width="123" height="30" align="center" bgcolor="#D5D5D5"> ค่าไฟฟ้า </th>
                <th rowspan="2" width="90" height="30" align="center" bgcolor="#D5D5D5"> เงินทั้งหมด </th>
                <th rowspan="2" width="123" height="30" align="center" bgcolor="#D5D5D5"> จำนวนเงิน (หน่วย:คน) </th>
                <th rowspan="2" width="123" height="30" align="center" bgcolor="#D5D5D5"> ผู้รับเงิน </th>
                <th rowspan="2" width="123" height="30" align="center" bgcolor="#D5D5D5"> ผู้จ่ายเงิน</th>
                </tr>
                <tr>
                <th width="100" height="30" align="center" bgcolor="#D5D5D5"> ครั้งก่อน </th>
                <th width="100" height="30" align="center" bgcolor="#D5D5D5"> ครั้งหลัง </th>
                <th width="90" height="30" align="center" bgcolor="#D5D5D5"> หน่วยค่าไฟ </th>
                </tr>
            </thead>
            <tbody>
            <?php
            while($objResult = mysqli_fetch_array($objQuery,MYSQLI_ASSOC))
            {
            $room1 = substr($objResult["room_id"], 1,1);
            $room2 = substr($objResult["room_id"], 2);
            $room_str = $room1."-".$room2;
            $room_id = $objResult["room_id"];
            $stay =" SELECT * FROM stay s 
                    INNER JOIN room AS  r ON s.room_id = r.room_id 
                    INNER JOIN student AS  st ON s.student_id = st.student_id
                    where s.semester_year = '$year' and s.room_id = '$room_id'  ";//stay
            $query_stay = mysqli_query($con,$stay);
            
            $month1 = number($month);
            if($month1 == 0 ){
                $month1 = 11;
                $month2 = Month($month1);
                $year1 = $year-1;
            }else{
                $month1 -= 1;
                $month2 = Month($month1);
                $year1 = $year;
            }
            
            $end =" SELECT r.room_id,u.power_charge FROM room AS  r  
                    INNER JOIN power_usage AS  u ON r.room_id = u.room_id
                    INNER JOIN room_type AS  t ON r.room_type_id = t.room_type_id
                    where t.room_type_id = '$room_type_id' and r.room_id LIKE '%$room_id%'
                    and u.month = '$month2' and u.semester_year = '$year1' order  by r.room_id ASC ";//stay
            $query_end = mysqli_query($con,$end);
            $obj_end = mysqli_fetch_array($query_end,MYSQLI_ASSOC);

            $count =" SELECT count(*) as sc FROM stay s INNER JOIN room AS  r ON s.room_id = r.room_id 
            INNER JOIN student AS  st ON s.student_id = st.student_id where s.semester_year = '$year' and s.room_id = '$room_id' ";//count
            $query_count = mysqli_query($con,$count);
            $obj_count = mysqli_fetch_array($query_count,MYSQLI_ASSOC);
            ?>
               
                <?php
                        $i = 1;
                        $j = 1;
                    while($obj_stay = mysqli_fetch_array($query_stay,MYSQLI_ASSOC)){
                        ?>
                        <tr >
                            <?php
                            if($j < $obj_count["sc"] && $j == 1 ){
                            ?>   
                                  <td  align="center" rowspan="<?php echo  $obj_count["sc"] ;?>" ><?php  echo $room_str;?></td>
                            <?php
                                $j = 1;
                                $mpdf = new mPDF('th', 'A4-L', '0', 'THSaraban');
                                $mpdf->use_kwt = true;
                                $mpdf->AddPage();
                            }
                            ?>  
                            <td align="center" height="35"><?php echo $obj_stay["student_id"];?></td>
                            <td align="left" height="35"><?php echo "&nbsp;&nbsp;".$obj_stay["student_prefix"]."  ".$obj_stay["student_name"]."  ".$obj_stay["student_lname"];?></td>  
                            <?php
                            if($i <= $obj_count["sc"] && $i == 1 ){
                            ?>                                        
                                <td align="center" rowspan="<?php echo  $obj_count["sc"] ;?>" ><?php echo $obj_end["power_charge"];?></td>
                                <td align="center" rowspan="<?php echo  $obj_count["sc"] ;?>" ><?php echo $objResult["power_charge"];?></td>
                                <td align="center" rowspan="<?php echo  $obj_count["sc"] ;?>" ><?php echo $objResult["power_charge"] - $obj_end["power_charge"] ;?></td>
                                <td align="center" rowspan="<?php echo  $obj_count["sc"] ;?>" ><?php echo ($objResult["power_charge"] - $obj_end["power_charge"]) * 4  ;?></td>
                                <?php $total += ($objResult["power_charge"] - $obj_end["power_charge"]) * 4 ;?>
                                
                            <?php
                            
                                $i = 1;
                            }
                            ?>  
                        <td align="center"><?php echo number_format((($objResult["power_charge"] - $obj_end["power_charge"]) * 4) / $obj_count['sc'])  ;?></td>
                        <td align="center"></td>    
                        <td align="center"></td>  
                        <?php $total2 += (($objResult["power_charge"] - $obj_end["power_charge"]) * 4) / $obj_count['sc'] ;?>
                        </tr>
                <?php
                $i++;
                $j++;
                    }if($obj_count["sc"] == 0 || $obj_count["sc"] == NULL ){
                     ?>
                        <tr >
                        <td  align="center" height="35" rowspan="<?php echo  $obj_count["sc"] ;?>" ><?php  echo $room_str;?></td>
                        <td  height="35"></td>
                        <td height="35"></td>                                      
                        <td align="center" height="35" rowspan="<?php echo  $obj_count["sc"] ;?>" ><?php echo $obj_end["power_charge"];?></td>
                        <td align="center" height="35" rowspan="<?php echo  $obj_count["sc"] ;?>" ><?php echo $objResult["power_charge"];?></td>
                        <td align="center" height="35" rowspan="<?php echo  $obj_count["sc"] ;?>" ><?php echo $objResult["power_charge"] - $obj_end["power_charge"] ;?></td>
                        <td align="center" height="35" rowspan="<?php echo  $obj_count["sc"] ;?>" ><?php echo ($objResult["power_charge"] - $obj_end["power_charge"]) * 4  ;?></td>
                        <td align="center" height="35"><?php echo number_format(($objResult["power_charge"] - $obj_end["power_charge"]) * 4) ;?><br></td>
                        <td align="center" height="35"></td>    
                        <td align="center" height="35"></td>  
                        <?php $total += ($objResult["power_charge"] - $obj_end["power_charge"]) * 4 ;?>
                        <?php $total2 += (($objResult["power_charge"] - $obj_end["power_charge"]) * 4) / $obj_count['sc'] ;?>
                    <?php
                    }
                    ?>
            <?php
            }
            ?>
            <tr>
                <td align="center" height="35"></td>  
                <td align="center" height="35"></td>  
                <td align="center" height="35"></td> 
                <td align="center" height="35"></td> 
                <td align="center" height="35"></td>  
                <td align="center" height="35">ยอดรวม</td>  
                <td align="center"  height="35" border="0"> <?php echo number_format($total,2) ;?></td>
                <td align="center" height="35"><?php echo number_format($total2,2) ;?></td>
                <td align="center" height="35"></td> 
                <td align="center" height="35"></td>  
            </tr>
            </tbody>
            </table>
        </center>
                                          
</body>
</html>
<?php
$html = ob_get_contents();        //เก็บค่า html ไว้ใน $html 
ob_end_clean();
$pdf = new mPDF('th', 'A4-L', '0', 'THSaraban');   
$pdf->SetAutoFont();
date_default_timezone_set("Asia/Bangkok");
$datenow = date('Y-m-d'); 
$now= DateTime::createFromFormat('Y-m-d', $datenow);
$now1 = $now->format('d/m/Y');
$Year_end = substr($now1, 6, 4);
$M_end = substr($now1, 0,5);
$Y_end = $Year_end+543;
$date_now3 = $M_end.'/'.$Y_end;
$pdf->SetHTMLHeader('วันที่พิมพ์ '. $date_now3 );
$pdf->SetDisplayMode('fullpage');
// $footer = "rggtrrtggt";
// $pdf->SetHTMLfooter($footer);
$pdf->WriteHTML($html, 2);
$pdf->WriteHTML($paragrafo);
$pdf->Output();         // เก็บไฟล์ html ที่แปลงแล้วไว้ใน MyPDF/MyPDF.pdf ถ้าต้องการให้แสด

?>
<?php
 function number($month){
  if($month == "มกราคม"){ $month = 0;}
  else if($month == "กุมภาพันธ์"){ $month = 1;}
  else if($month == "มีนาคม"){$month = 2;}
  else if($month == "เมษายน"){$month = 3;}
  else if($month == "พฤษภาคม"){$month = 4;}
  else if($month == "มิถุนายน"){$month = 5;}
  else if($month == "กรกฎาคม"){$month = 6;}
  else if($month == "สิงหาคม"){$month = 7;}
  else if($month == "กันยายน"){$month = 8;}
  else if($month == "ตุลาคม"){$month = 9;}
  else if($month == "พฤศจิกายน"){$month = 10;}
  else if($month == "ธันวาคม"){$month =11;}
 return "$month";
}
function Month($month){
    $month1 = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    $month = $month1[$month];
  return "$month";
}
?>