<?php
include('dbconn\dbconn.php');//INCLUDING DATABASE CONNECTIVITY FILE
	$stmt = $conn->prepare("DELETE FROM mastertable");//DELETE THE DATABASE RECORDS ONCE THE NEW DAY STARTS 
    $stmt->execute();
	date_default_timezone_set('Asia/Kolkata');//RETURNS CURRENT TIME IN INDIA
	$month=date("m");//EXTRACTING CURRENT MONTH
	$date=date("d");//EXTRACTING CURRENT DAY
	$stmt = $conn->prepare("SELECT rollno, name, dob, image FROM storedetails"); //EXTRACTING DATABASE RECORDS
    $stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $res)
	{	
		$dob=explode("-",$res['dob']);//EXTRACTING DATE AND MONTH FROM THE RECORDS
		if($month==$dob[1] && $date==$dob[2])//CHECKING WHETHER THE RECORD DOB IS CURRENT DAY OR NOT
		{
			//IF IT IS EQUAL THEN ENTERING THE RECORD INTO MASTER TABLE
			$stmt = $conn->prepare("INSERT INTO mastertable(rollno,name,image) 
			VALUES (:rollno, :name, :image)");
			$stmt->bindParam(':rollno',$res['rollno'] );
			$stmt->bindParam(':name', $res['name']);
			$stmt->bindParam(':image', $res['image']);
			$stmt->execute();
		}
	}
	//NOTIFIYING THAT THE RECORD HAS BEEN SUBMITTED
	echo "<CENTER><H1 class='p-5 font-weight-bold font-italic text-success'>DATA INSERTED INTO MASTER TABLE<H1></CENTER>";
?>