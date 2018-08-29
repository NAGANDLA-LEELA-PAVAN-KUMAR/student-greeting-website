<?php
include('dbconn\dbconn.php');//INCLUDING DATABASE CONNECTIVITY FILE
?>


<body>
<center><h1 class="text-primary"> WISH YOU A MANY MORE HAPPY RETURNS OF THE DAY</h1></center>
<div class="container">
<?php
	//EXTRACTING DATABASE RECORDS
	$stmt = $conn->prepare("SELECT rollno, name, image FROM mastertable"); 
    $stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $res) 
	{
		//DISPLAYING THE IMAGES
		$imgpath=$res['image'];
		echo "<CENTER><h2 class='studnames'>".$res['name']."</h2></center>";
		echo "<img src='studentimages/".$imgpath."'style='width:100%;height:950px;' class='mySlides'/>";
	
	}
?>
<script>
var myIndex = 0;
carousel();
function carousel() {
    var i;
    var x = document.getElementsByClassName("mySlides");//GETTING THE IMAGE  WHOSE CLASS NAME IS MYSLIDES
	var y=document.getElementsByClassName("studnames");
    for (i = 0; i < x.length; i++) {
       x[i].style.display = "none";  
	   y[i].style.display= "none";
    }
    myIndex++;
    if (myIndex > x.length) {myIndex = 1}    
    x[myIndex-1].style.display = "block";  
	y[myIndex-1].style.display="block";
    setTimeout(carousel, 2000); // Change image every 2 seconds
}
</script>
</div>
</body>