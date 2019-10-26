<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
  { 
header('location:index.php');
}
else{

if(isset($_POST['submit']))
{
$uid=$_SESSION['id'];
$state=$_POST['state'];
$noc=$_POST['noc'];
$complaintdetials=$_POST['complaindetails'];
$compfile=$_FILES["compfile"]["name"];



move_uploaded_file($_FILES["compfile"]["tmp_name"],"complaintdocs/".$_FILES["compfile"]["name"]);
$query=mysqli_query($con,"insert into tblcomplaints(userId,state,noc,complaintDetails,complaintFile) values('$uid','$state','$noc','$complaintdetials','$compfile')");
// code for show complaint number
$sql=mysqli_query($con,"select complaintNumber from tblcomplaints  order by complaintNumber desc limit 1");
while($row=mysqli_fetch_array($sql))
{
 $cmpn=$row['complaintNumber'];
}
$complainno=$cmpn;

echo '<script> alert("Your complain has been successfully filled and your complaintno is  "+"'.$complainno.'")</script>';
if($query) {
    $to = $_SESSION['login'];
    require '/usr/share/php/libphp-phpmailer/class.phpmailer.php';
    require '/usr/share/php/libphp-phpmailer/class.smtp.php';
    
    $mail = new PHPMailer;
    $mail->setFrom('');  //Add your cleanliness authority username here
    $mail->addAddress($to);
    $mail->isHTML(true);

    if (isset($_FILES['compfile']) &&
        $_FILES['compfile']['error'] == UPLOAD_ERR_OK) {
        $mail->AddAttachment($_FILES['compfile']['tmp_name'],
                             $_FILES['compfile']['name']);
    }
//$mail->addAttachment($_FILES['compfile']);
$mail->Subject = "Complaint Received";
$mail->Body =  "This email is to confirm that your email regarding cleanliness complain in your local area has been successfully forwarded to the concerned authority. \n\n Your complaint details are as follows: \n\n".
  "Nature of Complaint: ". $noc . "\n" ."Complaint Details: ". $complaintdetials ."." ."\n". "Along with the following attachment!";
              

$mail->IsSMTP();
$mail->SMTPSecure = 'ssl';
$mail->Host = 'ssl://smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Port = 465;

$mail->Username = ""; //Add your cleanliness authority email here

$mail->Password = 'cleanindia'; //Add your cleanliness authority email password here

$mail2 = new PHPMailer;

$mail2->setFrom( ' ',$_SESSION['login'],0); //Add your cleanliness authority email here
$mail2->addAddress('');   //Add your cleanliness authority email here    
$mail2->isHTML(true);                                 
if (isset($_FILES['compfile']) &&
        $_FILES['compfile']['error'] == UPLOAD_ERR_OK) {
        $mail2->AddAttachment($_FILES['compfile']['tmp_name'],
                             $_FILES['compfile']['name']);
    }
$mail2->Subject = $noc;
$mail2->Body    = $complaintdetials. "\n"."\n"."This email was sent by: ".$_SESSION['login'];

$mail2->IsSMTP();
$mail2->SMTPSecure = 'ssl';
$mail2->Host = 'ssl://smtp.gmail.com';
$mail2->SMTPAuth = true;
$mail2->Port = 465;

$mail2->Username = ""; //Add your cleanliness authority email here

$mail2->Password = ''; //Add your cleanliness authority email password here

if(!$mail2->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail2->ErrorInfo;
} else {
    echo 'Message has been sent';
}


if(!$mail->send()) {
  echo 'Email is not sent.';
  echo 'Email error: ' . $mail->ErrorInfo;

} else {
  echo 'Email has been sent.';
  
  }
}
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>NCS - User Register Complaint</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/js/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/js/bootstrap-daterangepicker/daterangepicker.css" />
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <script>

  </script>
  
  </head>

  <body>

  <section id="container" >
     <?php include("includes/header.php");?>
      <?php include("includes/sidebar.php");?>
      <section id="main-content">
          <section class="wrapper">
          	<h3><i class="fa fa-angle-right"></i> Register Complaint</h3>
          	
          	<!-- BASIC FORM ELELEMNTS -->
          	<div class="row mt">
          		<div class="col-lg-12">
                  <div class="form-panel">
                  	

                      <?php if($successmsg)
                      {?>
                      <div class="alert alert-success alert-dismissable">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <b>Well done!</b> <?php echo htmlentities($successmsg);?></div>
                      <?php }?>

   <?php if($errormsg)
                      {?>
                      <div class="alert alert-danger alert-dismissable">
 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <b>Oh snap!</b> </b> <?php echo htmlentities($errormsg);?></div>
                      <?php }?>

                      <form class="form-horizontal style-form" method="post" name="complaint" enctype="multipart/form-data" >





<div class="form-group">


<label class="col-sm-2 col-sm-2 control-label">Locality</label>
<div class="col-sm-4">
<select name="state" required="required" class="form-control">
<option value="">Select Locality</option>
<?php $sql=mysqli_query($con,"select stateName from state ");
while ($rw=mysqli_fetch_array($sql)) {
  ?>
  <option value="<?php echo htmlentities($rw['stateName']);?>"><?php echo htmlentities($rw['stateName']);?></option>
<?php
}
?>

</select>
</div>
</div>


<div class="form-group">
<label class="col-sm-2 col-sm-2 control-label">Nature of Complaint</label>
<div class="col-sm-4">
<input type="text" name="noc" required="required" value="" required="" class="form-control">
</div>

</div>

<div class="form-group">
<label class="col-sm-2 col-sm-2 control-label">Complaint Details (max 2000 words) </label>
<div class="col-sm-6">
<textarea  name="complaindetails" required="required" cols="10" rows="10" class="form-control" maxlength="2000"></textarea>
</div>
</div>
<div class="form-group">
<label class="col-sm-2 col-sm-2 control-label">Complaint Related Doc(if any) </label>
<div class="col-sm-6">
<input type="file" name="compfile" class="form-control" value="">
</div>
</div>



                          <div class="form-group">
                           <div class="col-sm-10" style="padding-left:25% ">
<button type="submit" name="submit" class="btn btn-primary">Submit</button>
</div>
</div>

                          </form>
                          </div>
                          </div>
                          </div>
                          
          	
          	
		</section>
      </section>
    <?php include("includes/footer.php");?>
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>


    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>

    <!--script for this page-->
    <script src="assets/js/jquery-ui-1.9.2.custom.min.js"></script>

	<!--custom switch-->
	<script src="assets/js/bootstrap-switch.js"></script>
	
	<!--custom tagsinput-->
	<script src="assets/js/jquery.tagsinput.js"></script>
	
	<!--custom checkbox & radio-->
	
	<script type="text/javascript" src="assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap-daterangepicker/date.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap-daterangepicker/daterangepicker.js"></script>
	
	<script type="text/javascript" src="assets/js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
	
	
	<script src="assets/js/form-component.js"></script>    
    
    
  <script>
      //custom select box

      $(function(){
          $('select.styled').customSelect();
      });

  </script>

  </body>
</html>
<?php } ?>
