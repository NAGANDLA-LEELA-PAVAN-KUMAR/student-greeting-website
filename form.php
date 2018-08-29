<?php
include('dbconn\dbconn.php');//INCLUDING DATABASE CONNECTIVITY FILE 
?>
<?php
//DECLARING VARIABLES
$mainErr="";
$nameErr="*";
$rollnoErr="* ";
$imageErr="* ";
$dobErr="* ";
$var=1;
$check=1;
?>
<?php
//CHECKING WHETHER THE FORM IS SUBMITTED OR NOT
if(isset($_POST['submit'])){
	//INSETING THE USER ENTERED DETAILS INTO VARIABLES
	$name =$_POST['studentname'];
    $dob=$_POST['studentdob'];
    $rollno=$_POST['studentrollno'];
	$filename=$_FILES["studentimage"]["name"];
	$tempname=$_FILES["studentimage"]["tmp_name"];
	//CHECKING WHETHER ALL THE FIELDS ARE NOT EMPTY
	if($name=='' || $dob=='' || $rollno=='' || $filename=='' || $tempname=='')
	{
		//IF THEY ARE NOT FILLED THEN ASSIGNING THE NOTIFICATION MASSAGE TO A VARIABLE
		$mainErr="* FIELDS MUST BE FILLED";
		//MAKING THE DATA CANNOT BE INSERT INTO DATABASE BY USING VAR VARIABLE
		$var=0;
	}
	//CHECKING WHETHER THE ENTERED NAME IS VALID NAME OR NOT
	if(!preg_match("/^[A-Za-z. ]*$/",$name))
	{
		//IF THEY ARE NOT A VALID NAME THEN ASSIGNING THE NOTIFICATION MASSAGE TO A VARIABLE
		$nameErr="* ONLY LETTERS AND WHITE SPACES ARE ALLOWED";
		//MAKING THE DATA CANNOT BE INSERT INTO DATABASE BY USING VAR VARIABLE
		$var=0;
	}
	//CHECKING WHETHER THE ENTERED ROLL NUMBER IS VALID OR NOT
	if(!preg_match("/^[A-Za-z0-9 ]*$/",$rollno))
	{
		//IF IT IS NOT A VALID ROLL NUMBER THEN ASSIGNING THE NOTIFICATION MASSAGE TO A VARIABLE
		$rollnoErr=" ONLY ALPHANUMERIC CHARACTERS ARE ALLOWED";
		//MAKING THE DATA CANNOT BE INSERT INTO DATABASE BY USING VAR VARIABLE
		$var=0;
	}
	//CHECKING WHETHER THE ENTERED ROLL NUMBER & FILENAME IS SAME OR NOT
	if(substr($filename,0,10)!=$rollno)
	{
		//IF THEY ARE NOT SAME THEN ASSIGNING THE NOTIFICATION MASSAGE TO A VARIABLE
		$imageErr=$imageErr." IMAGE FILE NAME MUST BE YOUR ROLL NUMBER<br>";
		//MAKING THE DATA CANNOT BE INSERT INTO DATABASE BY USING VAR VARIABLE
		$var=0;
	}
	//CHECKING WHETHER THE TMP_NAME & FILENAME ARE NOT EMPTY
	if($tempname!='' && $filename!='')
	{
		//EXTRACTING THE FILE EXTENSION
		$imageFileType = strtolower(pathinfo($filename,PATHINFO_EXTENSION));
		$data = getimagesize($tempname);
		$width = $data[0];
		$height = $data[1];
		//CHECKING WHETHER THE FILE EXTENSION IS OF VALID TYPE
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") 
		{
			//IF IT IS NOT VALID THEN ASSIGNING THE NOTIFICATION MASSAGE TO A VARIABLE
			$imageErr=$imageErr." ONLY JPG, JPEG, PNG  FILES ARE ALLOWED <br>";
			//MAKING THE DATA CANNOT BE INSERT INTO DATABASE BY USING VAR VARIABLE
			$var = 0;
		}
		/*if($width<=1080 && $height<=850){
			$imageErr=$imageErr." IMAGE WIDTH SHOULD BE LESS THAN 1080px & HEIGHT SHOULD BE LESS THAN 850px ";
			$var = 0;		
		}*/
	}
	//EXTRACTING THE VALUES FROM THE DATABASE TO AVOID DUPLICATE ENTRIES
	$stmt = $conn->prepare("SELECT rollno, name, dob, image FROM storedetails"); 
    $stmt->execute();
	$result = $stmt->fetchAll();
	//CHECKING WHETHER THE ROLL NUMBER IS PRESENT IN DATABASE OR NOT
	foreach($result as $res){
		if($res['rollno']==$rollno)
		{
			//IF IT IS ALREADY EXISTS IN THE DATABASE THEN MAKING THE DETAILS CANNOT BE INSERT INTO DATABASE BY USINF CHECK VARIABLE
			$check=0;
			$var=0;
		}
	}
	//IF IT IS ALREADY EXISTED IN THE DATABASE THEN SHOWING A MESSAGE TO THE USER 
	//THAT THE DATA IS ALREADY EXISTED
	if($check==0)
	{
		echo "<script>alert('ALREADY SUBMITTED')</script>";
	}	
	//IF ALL CONDITIONS ARE VALID THEN DATA IS INSERTED INTO DATA BASE
	if($var!=0){
	$folder="studentimages/".$filename;
	move_uploaded_file($tempname,$folder);
    $stmt = $conn->prepare("INSERT INTO storedetails(rollno,name,dob,image) 
    VALUES (:rollno, :name, :dob, :image)");
    $stmt->bindParam(':rollno', $rollno);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':dob', $dob);
	$stmt->bindParam(':image', $filename);
	$stmt->execute();
	//NOTIFIYING THAT THE FORM SUCESSFULLY SUBMITTED
	echo "<script>alert('SUCESSFULLY SUBMITTED')</script>";
	}
}
?>

<html>
<body>
	<div style="padding:25px;" class="rounded">
	<center>
		<header class="font-italic  font-weight-bold display-4 text-success" style="text-shadow:2px 2px 2px black" >STUDENT DOB REGISTRATION FORM
		</header>
	</center>
	</div>
	<br>
	<div class="row">
		<div class="offset-lg-3 col-lg-6">
			<div class=" bg-secondary rounded text-white font-weight-bold">
				<ul>
					<li class="p-2">" <span style="color:red;font-size:25px;">*</span> " &nbsp;FIELDS MUST BE FILLED</li>
					<li class="p-1">IMAGE FILE NAME MUST BE YOUR ROLL NUMBER</li>
					<li class="p-1">IMAGE WIDTH SHOULD BE LESS THAN 1080px &amp; HEIGHT SHOULD BE LESS THAN 850px </li>
					<li class="p-2">ONLY JPG, JPEG, PNG  FILES ARE ALLOWED</li>
				</ul>
			</div>
		</div>
		<div class="col-lg-3">
		</div>
	</div>
	<div class="row">
		<div class="offset-lg-4 col-lg-4">
			<?php echo "<h6 style='color:red;font-weight:bold;font-size:20px;'>".$mainErr."</h6><br>";?>
			<form action="form.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label>NAME :</label>
					<?php echo "<h6 style='float:right;color:red;font-weight:bold;font-size:20px;'>".$nameErr."</h6>";?>
					<input type="text" class="form-control" name="studentname" placeholder="Enter your name">
				</div>
				<div class="form-group">
					<label>DATE OF BIRTH:</label>
					<?php echo "<h6 style='float:right;color:red;font-weight:bold;font-size:20px;'>".$dobErr."</h6>";?>
					<input type="date" name='studentdob' class="form-control col-12">
				</div>
				<div class="form-group">
					<label>ROLL NO:</label>
					<?php echo "<h6 style='float:right;color:red;font-weight:bold;font-size:20px;'>".$rollnoErr."</h6>";?>
					<input type="text" name='studentrollno' class="form-control">
				</div>
				<div class="form-group">
					<label for="exampleFormControlFile1">UPLOAD YOUR IMAGE</label>
					<?php echo "<h6 style='float:right;color:red;font-weight:bold;font-size:20px;'>".$imageErr."</h6>";?>
					<input type="file" class="form-control-file" name="studentimage" id="exampleFormControlFile1">
				</div>
				<BR>
				<button type="submit" class="btn btn-primary btn-lg btn-block" name="submit">Submit</button>
			</form>
		</div>
	</div>
</body>
</html>
