
<?php 
$conn= new mysqli('localhost','root','','cafe_billing_db')or die("Could not connect to mysql".mysqli_error($con));
?>

<?php 

if (isset($_POST['idM'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$uname = validate($_POST['idM']);

	if (empty($uname)) {
		header("Location:  index.php?#");
	    exit();
	}else{

        
        
        $query = "SELECT * FROM mesa WHERE idmesa='$uname'";

		$res = $conn->query($query);

		if (mysqli_num_rows($res) === 1) {
            header("Location: menu.php");
		        exit();
			$row = mysqli_fetch_assoc($res);
		}else{
			header("Location: index.php?#error=Incorect idmesa");
	        exit();
		}
	}
	
}