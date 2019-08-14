<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	session_start();
	// Your database details might be different
	$mysqli = mysqli_connect("127.0.0.1", "root", "", "dbuser");

	$email = isset($_POST["loginName"]) ? $_POST["loginName"] : false;
	$pass = isset($_POST["loginPassw"]) ? $_POST["loginPassw"] : false;	
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 3</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Mathopatona Paballo Matabane">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
				if(isset($_POST['loginP'])){
					if(isset($_SESSION['password']))
							{
								$email =$_SESSION['email'];
								$pass =$_SESSION['password'];
							$uploadFile = $_FILES["picToUpload"]; 
							 $numFiles = count($uploadFile["name"]);

							for($i = 0; $i < $numFiles; $i++)
							{
									
									if($uploadFile["type"][$i] == "image/jpeg" || $uploadFile["type"][$i] == " image/jpg"  ){
										if($uploadFile["size"][$i] < 1000000)
										{
											move_uploaded_file($uploadFile["tmp_name"][$i],"gallery/" . $uploadFile["name"][$i]);
											$userid =$_SESSION['userID'];
											$name =$uploadFile["name"][$i];
											$query ="INSERT INTO  tbgallery(user_id,filename) VALUES('$userid','$name') ";
											$res = mysqli_query($mysqli, $query) == TRUE;
										}
										else
										{
											echo 	'<div class="alert alert-danger mt-3" role="alert">
	  													incorrect file SIZE, file was not uploaded
	  												</div>';
										
										}
									}
										else
											echo 	'<div class="alert alert-danger mt-3" role="alert">
	  											 incorrect file format, file was not uploaded
	  											</div>';
										
							} 
					
							}
				}
			
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					$pass = $row['password'];
					$email = $row['email'];
					$_SESSION['userID'] =$row['user_id'];
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form enctype='multipart/form-data' action='login.php' method='POST'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload[]' id='picToUpload' multiple='multiple'/><br/>
									<input type='hidden' id='loginEmail' class='form-control' value='$email' name='loginName'>
									<input type='hidden' id='loginPass' class='form-control' value='$pass' name='loginP'>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>";
								echo "<h1>Image Gallery</h1>";
						  		$userid =$_SESSION['userID'];
						  		$query = "SELECT * FROM tbgallery WHERE user_id ='$userid'";
								$res = $mysqli->query($query);
								echo "<div class='imageGallery row'>";
								while($row = mysqli_fetch_assoc($res)) { 
									$imgname ="gallery/".$row['filename'];
							  		echo "<div class='col-3' style='background-image: url(".$imgname.")'> </div>
							  	";
							  
							  }

						  	echo "</div></form>";

				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		
		?>
	</div>
</body>
</html>