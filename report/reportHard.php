<?php
// require ('./fpdf181/html_table/html_table.php');
// include './components/db.php';
include('./fpdf181/fpdf.php'); 

$room_type = $_POST['room_type'];
$month = $_POST['month'];
$year = $_POST['year'];
      if($room_type == 'หอชาย'){
        $room_type_id = 1; 
      }if($room_type == 'หอพักหญิง 1'){
        $room_type_id = 2; 
        $room_id = 'f1'; 
      }if($room_type == 'หอพักหญิง 2'){
        $room_type_id = 3; 
        $room_id = 'f2'; 
      }
$search = "";
if($room_id != NULL){
    $search = " and r.room_id LIKE '%$room_id%' ";
}if($month != NULL){
    $search = " and u.month = '$month' ".$search;
}if($year != NULL){
    $search = " and u.semester_year = '$year' ".$search;
}

class PDF extends FPDF
{
// Page header
function Header(){
    date_default_timezone_set("Asia/Bangkok");
    $datenow = date('Y-m-d'); 
      $now= DateTime::createFromFormat('Y-m-d', $datenow);
      $now1 = $now->format('d/m/Y');
      $Year_end = substr($now1, 6, 4);
      $M_end = substr($now1, 0,5);
      $Y_end = $Year_end+543;
      $date_now3 = $M_end.'/'.$Y_end;
    $this->SetY(1);
    $this->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->SetFont('THSarabunNew','',12);
    $this->Cell(0,15,iconv( 'UTF-8','cp874' , 'วันที่พิมพ์ ' ).iconv( 'UTF-8','cp874' , $date_now3  ),0,0,'L');
    $this->Ln(12);

    // $this->Cell(0,10,$this->PageNo(),0,0,'L');
    $room_type = $_POST['room_type'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    global $title;
    $this->AddFont('angsa','','angsa.php');
    $this->SetFont('angsa','B',18);
    $w = $this->GetStringWidth($title)+6;

    $this->SetX((210-$w)/2);
    $this->SetLineWidth(0);
    $this->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->SetFont('THSarabunNew','B',18);
    $this->Cell($w,10,iconv( 'UTF-8','cp874' , 'รายงานสรุปผลการแจ้งซ่อม' ),0,0,'C');
    $this->Ln(7);
    $this->Cell(190,10,iconv( 'UTF-8','cp874' , 'ประจำเดือน ' ).iconv( 'UTF-8','cp874' , $month ).'  '.iconv( 'UTF-8','cp874' , 'ปี ' ).iconv( 'UTF-8','cp874' , $year ),0,0,'C');
    $this->Ln(7);
    $this->Cell(190,10,'( '.iconv( 'UTF-8','cp874' , $room_type ).' )',0,0,'C');
    $this->Ln(15);
    $this->SetFillColor(214,206,206);
    $this->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->SetFont('THSarabunNew','',16);
    $this->SetX((100-$w)/2);
    $this->Cell(25,16,iconv( 'UTF-8','cp874' , 'การแจ้งซ่อม' ),1,0,'C',true);
    $this->Cell(30,16,iconv( 'UTF-8','cp874' , 'สำเร็จ' ),1,0,'C',true);
    $this->Cell(30,16,iconv( 'UTF-8','cp874' , 'รอดำเนินการ' ),1,0,'C',true);
    $this->Cell(30,16,iconv( 'UTF-8','cp874' , 'ไม่สำเร็จ' ),1,0,'C',true);
    // $this->MultiCell(20,8,iconv( 'UTF-8','cp874' , 'จำนวนเงิน (หน่วย:คน)' ),1,0,'C',true);
    $this->Ln(16); 
    // $this->Cell(150,16,iconv( 'UTF-8','cp874' , '' ),0,0,'C');
    // $this->Cell(20,16,iconv( 'UTF-8','cp874' , 'ค่าปรับ' ),1,0,'C',true);
    // $this->MultiCell(20,16,iconv( 'UTF-8','cp874' , 'รวมทั้งหมด' ),1,0,'C',true);
}

function Chapter(){
    
    $this->SetFont('Arial','',12);
    $this->SetFillColor(200,220,255);
    $this->Cell(0,6,"Chapter $num : $label",0,1,'L',true);
    $this->Ln(4);
}

function Body(){
}

// Page footer
function Footer(){
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Times','',8);
    // Page number
    $this->Cell(0,10,$this->PageNo(),0,0,'C');
    // $this->Cell(0,10,$this->PageNo(),0,0,'L');
}
}
$pdf = new PDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetTitle('รายงานสรุปผลการแจ้งซ่อม',[isUTF8]);
// $sql = "SELECT * FROM power_usage_detail AS  put  
// INNER JOIN power_usage AS  u ON put.power_usage_id = u.power_usage_id
// INNER JOIN room AS  r ON u.room_id = r.room_id
// INNER JOIN room_type AS  t ON r.room_type_id = t.room_type_id
// INNER JOIN student AS  s ON put.student_id = s.student_id
// where t.room_type_id = '$room_type_id' and put.detail_status = 'ยังไม่ชำระเงิน(ล่าช้า)' ".$search." GROUP BY u.room_id  order by put.power_usage_detail_id ASC ";
// $query = mysqli_query($con,$sql);
      
$i = 1;

// while($result = mysqli_fetch_array($query,MYSQLI_ASSOC)){
//     $power_usage_id = $result['power_usage_id'] ;
//     $student_id = $result['student_id'] ;

//     $count1 =" SELECT count(*) as c FROM power_usage_detail 
//                         where detail_status = 'ยังไม่ชำระเงิน(ล่าช้า)' 
//                         and power_usage_id = '$power_usage_id' ";//count
//     $query_count1 = mysqli_query($con,$count1);
//     $obj_count = mysqli_fetch_array($query_count1,MYSQLI_ASSOC);

//     $room_id = $result["room_id"];
//     $sql_puo =" SELECT * FROM power_usage_detail AS  put  
//               INNER JOIN power_usage AS  u ON put.power_usage_id = u.power_usage_id
//               INNER JOIN room AS  r ON u.room_id = r.room_id
//               INNER JOIN room_type AS  t ON r.room_type_id = t.room_type_id
//               INNER JOIN student AS  s ON put.student_id = s.student_id
//               left JOIN majors AS  m ON s.majors_id = m.majors_id
//               where t.room_type_id = '$room_type_id'
//                 and u.room_id = '$room_id' and put.detail_status = 'ยังไม่ชำระเงิน(ล่าช้า)' ".$search ;//count
//     $query_puo = mysqli_query($con,$sql_puo);

//     $room1 = substr($result["room_id"], 1,1);
//     $room2 = substr($result["room_id"], 2);
//     $room_str = $room1."-".$room2;
//     $i = 1;

     $j = 8 * 1 ;
     $count=0;
     $type="ไฟฟ้า";
 //   $pdf->Cell(38,$j,$room_str,1,0,'C');
    $fine = 100;
    $list = array ('','','','','');
    while(true){
        if($i > 1){
            //$pdf->Cell(20,8,'',0,0,'C');
            $c = 40 ;
        }else{
            $c = 40 ;
        }    
        if( $type == "ไฟฟ้า") {
            $list = array ('พัดลม','หลอดไฟ','เต้ารับ','สวิตไฟ');
        }
        if( $type == "ครุภัณฑ์") {
            $list = array ('ตู้','โต๊ะ','เตียง','เก้าอีี้');
        }
        if( $type == "ประตู/หน้าต่าง") {
            $list = array ('ประตูห่อง','บานเกล็ด','มุ้งลวด','ประตูบานเลื่อน');
        }
    $pdf->Cell(37,8,iconv( 'UTF-8','cp874' , $list[$count] ),1,0,'C');
    // $pdf->Cell($c+20,8,iconv( 'UTF-8','cp874' ,1 ),1,0,'C');
    $pdf->Cell(25,8,iconv( 'UTF-8','cp874' ,1 ),1,0,'C');
    $pdf->Cell(30,8,iconv( 'UTF-8','cp874' , number_format(5) ),1,0,'C');
    $pdf->Cell(30,8,iconv( 'UTF-8','cp874' , $fine ),1,0,'C');
    $pdf->Cell(30,8,iconv( 'UTF-8','cp874' , $fine ),1,0,'C');
    $pdf->Cell(50,8,iconv( 'UTF-8','cp874' , '' ),0,0,'C');
    $pdf->Cell(20,8,'',0,0,'C');
    $pdf->Ln(8 );
    $i++;
    $count++;
    
        if ($i==5) {
            break;  
        } 
    }
    
// }
// $pdf->SetFillColor(214,206,206);
// $pdf->Cell(150,8,iconv( 'UTF-8','cp874' , 'ยอดรวม(จำนวนเงินไม่รวมค่าปรับ)'),1,0,'C');
// $pdf->Cell(40,8,iconv( 'UTF-8','cp874' , number_format($amount).' บาท'),1,0,'C',true);
// $pdf->Ln();
// $pdf->Cell(150,8,iconv( 'UTF-8','cp874' , 'ยอดรวม(จำนวนเงินรวมค่าปรับ)'),1,0,'C');
// $pdf->Cell(40,8,iconv( 'UTF-8','cp874' , number_format($amount_fine1).' บาท'),1,0,'C',true);
$pdf->Output( 'adminreport.pdf' , 'I' );

?>