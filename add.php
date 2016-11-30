<!DOCTYPE html>
<html>
<head>
	<title>Add values to database</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="Demo project">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
	<style type="text/css"></style>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<style>
		#newFields input {
		    display:block;
		}
	</style>

	<script>
		$(function() {

		    var input = $('<input type="text" name="ZXY[]" />');
		    var newFields = $('');

		    $('#NumBead').bind('blur keyup change', function() {
		        var n = this.value || 0;
		        if (n+1) {
		            if (n > newFields.length) {
		                addFields(n);
		            } else {
		                removeFields(n);
		            }
		        }
		    });

		    function addFields(n) {
		        for (i = newFields.length; i < n; i++) {
		            var newInput = input.clone();
		            newFields = newFields.add(newInput);
		            newInput.appendTo('#newFields');
		        }
		    }

		    function removeFields(n) {
		        var removeField = newFields.slice(n).remove();
		        newFields = newFields.not(removeField);
		    }
		});
	</script>

	
</head>
<body>
	
	<!--Load the AJAX API-->
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<!-- Connection to the database -->
	<?php
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=PSF;charset=utf8','root','admin');
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}

	//Allows to get the name of the tables from the PSF database
	$choiceMicroscope = $bdd->query('select table_name from information_schema.tables where table_schema="PSF"');

	?>
	<center>
		<form method="POST" action="add.php" onsubmit="return confirm('Verify the data. Are you ok with what you entered ?');" enctype="multipart/form-data">
			<select id="Microscope" name="Microscope" onChange="hideshow();" onMouseDown="document.getElementById('ObjMagn').selectedIndex=0;" required>

				<option value="None">Select Microscope</option>
				<?php

				//Array for the different microscopes
				$table_list = array();
				//Security for getting the microscope without any SQL injections
				$numMic = 0;

				while($choice = $choiceMicroscope->fetch())
				{
					?>
					<!-- Loop to go through the different microscopes and get them in a menu -->
					<option value="<?php echo $numMic ?>"><?php echo $choice['table_name']; ?></option>
					<?php
					//Incrementation for the safety
					$numMic++;
					//Add the microscope to the table
					array_push($table_list,$choice['table_name']);
				}
				?>
			</select>

			<br>

			<!-- Menu with all the ObjMagn possible -->
			<select id="ObjMagn" name="ObjMagn" style="display:none" onChange="hideshow();" required>
				<option value="">Select Objective Magnification</option>
				<option value="40x">40x</option>
				<option value="60x">60x</option>
				<option value="63x">63x</option>
				<option value="100x">100x</option>
				<option value="20x">20x</option>
			</select>


			<!-- Menu with all the ObjNA possible -->
			<select id="NA" name="NA" style="display:none" required>
				<option value="">Select Objective Numerical Aperture</option>
				<option value="1.3">1.3</option>
				<option value="1.4">1.4</option>
				<option value="1.42">1.42</option>
				<option value="1.46">1.46</option>
				<option value="0.75">0.75</option>
				<option value="0.95">0.95</option>
				<option value="1.0">1.0</option>
			</select>

			<!-- Menu with all the AU possible -->
			<select id="AU" name="AU" style="display:none" required>
				<option value= "">Select Airy Unit</option>
				<option value= "1">1</option>
				<option value="Max">Max</option>
			</select>

			<br>

			<!-- Calendar to add the date of the values -->
			Date of the values (required) Format is YYYY-MM-DD
			<input type="date" name="Date" id="Date" required />

			<br>

			<!-- Number of beads. Simple or multiple values, separated by "," -->
			Number of Bead (required)
			<input type="number" name="NumBead" id="NumBead" required multiple>

			<br>

			Actual values for each bead in this format Z;X;Y (you can copy from the MIPs plugin output FWHMValues.txt)
			<div id="newFields"></div> 	

			<br>

			Your name (required)
			<input type="text" name="Firstname" value="Unknown" required>

			<br>

			<!-- Text area to enter as much settings as you want -->
			Settings (not required)
			<textarea name="Settings" id="Settings"></textarea>

			<br>

			Comments (not required)
			<input type="text" name="Comments" id="Comments" value="No comments">

			<br>


			You can upload one image here
			<input type="file" name="image">

			<br>

			<!--Upload a picture
			<input type="file" name="uploadFile">

			<br>-->

			<!-- Input button -->
			<input type="Submit" value="Send" id="Send"  name="Send" style="display:none">
		</form >

		<?php

		//echo ;

		?>

	</center>

	<script type='text/javascript'>


		//Function to hide and/or show the different menus depending of the previous one(s)
		function hideshow()
		{
			var obj= document.getElementById("ObjMagn"); //Values of the menu ObjMagn
			var sub= document.getElementById("Send"); //Get the button to hide/show it
			var au= document.getElementById("AU"); //Values of the menu AU
			var mic = document.getElementById("Microscope"); //Values of the menu Microscope
			var na = document.getElementById("NA"); //Values of the menu NA
			var array = <?php echo json_encode($table_list); ?>; //Array of the microscopes encoded in JS
			var micChoice1 = mic.options[mic.selectedIndex].value; //Get the number of the microscope selected
			var micChoice = array[micChoice1]; //Name of the selected microscope
			var objChoice = obj.options[obj.selectedIndex].value; //Get the Obj menu to hide/show it

			//If the microscope selected is different than a LSM
			if (micChoice != "None" && micChoice.substr(0,3) != "LSM")
			{	
				//If it's CellR
				if (micChoice == "CellR")
				{
					//Only show the corresponding options
					obj.style.display="block";
					obj[1].style.display="block";
					obj[2].style.display="block";
					obj[3].style.display="none";
					obj[4].style.display="none";
					obj[5].style.display="block";
					sub.style.display="block";
					au.required=false;
					au.style.display="none";
					
					//Hide the menu
					if(objChoice != "None")
					{
						switch (objChoice)
						{
							case "60x" :
								na.style.display="block";
								na[0].style.display="none";
								na[1].style.display="none";
								na[2].style.display="none";
								na[3].style.display="block";
								na.selectedIndex = 3;
								na[4].style.display="none";
								na[5].style.display="none";
								na[6].style.display="none";
								na[7].style.display="none";
							break;
							case "20x" :
								na.style.display="block";
								na[0].style.display="none";
								na[1].style.display="none";
								na[2].style.display="none";
								na[3].style.display="none";
								na.selectedIndex = 5;
								na[4].style.display="none";
								na[5].style.display="block";
								na[6].style.display="none";
								na[7].style.display="none";
							break;
							case "40x" :
								na.style.display="block";
								na[0].style.display="none";
								na[1].style.display="none";
								na[2].style.display="none";
								na[3].style.display="none";
								na.selectedIndex = 6;
								na[4].style.display="none";
								na[5].style.display="none";
								na[6].style.display="block";
								na[7].style.display="none";
						}
						
					}
					//Show it
					else
					{
						
						na.style.display="none";
					}
				}
				//If it's the AxioObserver
				else
				{
					obj.style.display="block";
					obj[1].style.display="block";
					obj[2].style.display="none";
					obj[3].style.display="block";
					obj[4].style.display="block";
					obj[5].style.display="none";
					sub.style.display="block";
					au.required=false;
					au.style.display="none";
					//Depending on the choice we show/hide the corresponding options
					if(objChoice != "None")
					{
						switch (objChoice)
						{
							case "40x" :
								//alert("40x");
								na.style.display="block";
								na[0].style.display="none";
								na[1].style.display="block";
								na.selectedIndex = 1;
								na[2].style.display="none";
								na[3].style.display="none";
								na[4].style.display="none";
								na[5].style.display="none";
								na[6].style.display="none";
								na[7].style.display="none";
								break;
								case "63x" :
								na.style.display="block";
								na[0].style.display="none";
								na[1].style.display="none";
								na[2].style.display="block";
								na.selectedIndex = 2;
								na[3].style.display="none";
								na[4].style.display="none";
								na[5].style.display="none";
								na[6].style.display="none";
								na[7].style.display="none";
								break;
								case "100x" :
								na.style.display="block";
								na[0].style.display="none";
								na[1].style.display="none";
								na[2].style.display="block";
								na.selectedIndex = 2;
								na[3].style.display="none";
								na[4].style.display="none";
								na[5].style.display="none";
								na[6].style.display="none";
								na[7].style.display="none";
								break;	
							}
					}
					//else hide it
					else
					{
						na.style.display="none";
					}
				}
			}
			//Test if the microscope's a LSM
			else if(micChoice != "None" && micChoice.substr(0,3) == "LSM")
			{
				//Depending on the choices, show/hide the corresponding options
				switch (micChoice)
				{
					case "LSM700":
						obj.style.display="block";
						obj[1].style.display="block";
						obj[2].style.display="none";
						obj[3].style.display="block";
						obj[4].style.display="none";
						obj[5].style.display="none";
						sub.style.display="block";
						au.required = true;
						au.style.display="block";
						if(objChoice != "None")
						{
							switch (objChoice)
							{
								case "40x" :
								na.style.display="block";
								na[0].style.display="none";
								na[1].style.display="block";
								na.selectedIndex = 1;
								na[2].style.display="none";
								na[3].style.display="none";
								na[4].style.display="none";
								na[5].style.display="none";
								na[6].style.display="none";
								na[7].style.display="none";
								break;
								case "63x" :
								na.style.display="block";
								na[0].style.display="none";
								na[1].style.display="none";
								na[2].style.display="block";
								na.selectedIndex = 2;
								na[3].style.display="none";
								na[4].style.display="none";
								na[5].style.display="none";
								na[6].style.display="none";
								na[7].style.display="none";
								break;
							}
						}
						else
						{
							na.style.display="none";
						}
					break;
					case "LSM710":
						obj.style.display="block";
						obj[1].style.display="block";
						obj[2].style.display="none";
						obj[3].style.display="none";
						obj[4].style.display="none";
						obj[5].style.display="block";
						sub.style.display="block";
						au.required = true;
						au.style.display="block";
						if(objChoice != "None")
						{
							switch (objChoice)
							{
								case "40x" :
								na.style.display="block";
								na[0].style.display="block";
								na[1].style.display="block";
								na[2].style.display="block";
								na.selectedIndex = 0;
								na[3].style.display="none";
								na[4].style.display="none";
								na[5].style.display="none";
								na[6].style.display="none";
								na[7].style.display="none";
								break;
								case "20x" :
								na.style.display="block";
								na[0].style.display="none";
								na[1].style.display="none";
								na[2].style.display="none";
								na.selectedIndex = 7;
								na[3].style.display="none";
								na[4].style.display="none";
								na[5].style.display="none";
								na[6].style.display="none";
								na[7].style.display="block";
								break;
							}
						}
						else
						{
							na.style.display="none";
						}
					break;
					case "LSM780":
						obj.style.display="block";
						obj[1].style.display="none";
						obj[2].style.display="none";
						obj[3].style.display="block";
						obj[4].style.display="block";
						obj[5].style.display="none";
						sub.style.display="block";
						au.required = true;
						au.style.display="block";
						if(objChoice != "None")
						{
							switch (objChoice)
							{
								case "63x" :
								na.style.display="block";
								na[0].style.display="none";
								na[1].style.display="none";
								na[2].style.display="block";
								na.selectedIndex = 2;
								na[3].style.display="none";
								na[4].style.display="none";
								na[5].style.display="none";
								na[6].style.display="none";
								na[7].style.display="none";
								break;
								case "100x" :
								na.style.display="block";
								na[0].style.display="none";
								na[1].style.display="none";
								na[2].style.display="none";
								na[3].style.display="none";
								na[4].style.display="block";
								na.selectedIndex = 4;
								na[5].style.display="none";
								na[6].style.display="none";
								na[7].style.display="none";

							}
						}
						else
						{
							na.style.display="none";
						}
					break;
					case "LSM880":
						obj.style.display="block";
						obj[1].style.display="block";
						obj[2].style.display="none";
						obj[3].style.display="block";
						obj[4].style.display="none";
						obj[5].style.display="none";
						sub.style.display="block";
						au.required = true;
						au.style.display="block";
						if(objChoice != "None")
						{
							switch (objChoice)
							{
								case "40x" :
								na.style.display="block";
								na[0].style.display="none";
								na[1].style.display="none";
								na[2].style.display="block";
								na.selectedIndex = 2;
								na[3].style.display="none";
								na[4].style.display="none";
								na[5].style.display="none";
								na[6].style.display="none";
								na[7].style.display="none";
								break;
								case "63x" :
								na.style.display="block";
								na[0].style.display="none";
								na[1].style.display="none";
								na[2].style.display="block";								
								na.selectedIndex = 2;
								na[3].style.display="none";
								na[4].style.display="none";
								na[5].style.display="none";
								na[6].style.display="none";
								na[7].style.display="none";

							}
						}
						else
						{
							na.style.display="none";
						}
					break;
				}
			}
			else
			{
				obj.style.display="none";
				sub.style.display="none";
				au.required=false;
				au.style.display="none";
				na.style.display="none";
			}
		}
		</script>

		<?php

		//$NumberBead = $_POST['NumBead'];
		/*echo $NumberBead;
		$ActVal = $_POST['ZXY'];
		for ($i=0; $i<$NumberBead;$i++)
		{
			$ZXY = preg_split("/[;]+/", $ActVal[$i]);
			echo "X :"+$ZXY[1]+" Y : "+$ZXY[2]+" Z: "+$ZXY[0];
		}
		*/
		//If submit button clicked
		if(isset($_POST['Send']))
		{
			//for($n=1;$n<=$NumberBead;$n++)
			//{
				//Get all the variables
				$Microscope = $table_list[$_POST['Microscope']];
				$Date = $_POST['Date'];
				$NumberBead = $_POST['NumBead'];
				$ObjMagn = $_POST['ObjMagn'];
				$ObjNA = $_POST['NA'];
				$AU = $_POST['AU'];
				$ActVal = $_POST['ZXY'];
				$Settings = $_POST['Settings'];
				$NameUser = $_POST['Firstname'];
				$Comments = $_POST['Comments'];

				//If the microscope's a LSM
				if(preg_match('/LSM/i',$Microscope))
				{
					//Get the theoretical values by a secured request
					$query = "Select LTVx,LTVy,LTVz from $Microscope where ObjMagn=? and ROUND(ObjNA)=ROUND(?) AND AU = ?;";
					$stmt = $bdd->prepare($query);
					$stmt -> execute(array($ObjMagn,$ObjNA,$AU));
					$Values = $stmt->fetch();

					//Get the values for each dimension
					$LTVx = $Values['LTVx'];
					$LTVy = $Values['LTVy'];
					$LTVz = $Values['LTVz'];

					if ($LTVx == "")
						$LTVx = 0;
					if ($LTVy == "")
						$LTVy = 0;
					if ($LTVz == "")
						$LTVz = 0;

					//Get the different values separated by ","
					//$NumberBead = $NumberBead;
					

					//If the number of values for each field is different, then fail
					/*if ((sizeof($NumberBead) != sizeof($LAVx)) || (sizeof($NumberBead) != sizeof($LAVy)) || (sizeof($NumberBead) != sizeof($LAVz)))
					{
						echo '<meta http-equiv="Refresh" content="0;url=fail.php">';
					}
					//Else ok continue
					else
					{*/
						//For each value add it to the database by a secure request
						for ($i=0; $i<$NumberBead;$i++)
						{

							//$LAVx = preg_split("/[,]+/", $LAVx);
							//$LAVy = preg_split("/[,]+/", $LAVy);
							//$LAVz = preg_split("/[,]+/", $LAVz);

							//echo "LSM";
							//echo $i+1;
							//echo $ActVal[$i];						
							$ZXY = preg_split("/[;]+/", $ActVal[$i]);
							//echo $ZXY[0];

							$query="Insert into $Microscope (Date,NumberBead,ObjMagn,ObjNA,AU,LTVx,LTVy,LTVz,LAVx,LAVy,LAVz,NameUser,Settings,Comment) 
							VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
							$stmt = $bdd->prepare($query);
							$stmt->execute(array( $Date, $i+1,
								$ObjMagn, $ObjNA, $AU, $LTVx, $LTVy, $LTVz, $ZXY[1],
								$ZXY[2], $ZXY[0], $NameUser, $Settings,$Comments));

							/*$query2="Insert into $Microscope (Date,NumberBead,ObjMagn,ObjNA,AU,LTVx,LTVy,LTVz,LAVx,LAVy,LAVz,NameUser,Settings,Comment) 
							VALUES ($Date, $i+1,
								$ObjMagn, $ObjNA, $AU, $LTVx, $LTVy, $LTVz, $ZXY[1],
								$ZXY[2], $ZXY[0], $NameUser, $Settings,$Comments);";
							echo $query2;*/
						}

						if(isset($_FILES['image'])){
							$errors= array();
							$file_name = $_FILES['image']['name'];
							$file_size =$_FILES['image']['size'];
							$file_tmp =$_FILES['image']['tmp_name'];
							$file_type=$_FILES['image']['type'];
							$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

							$expensions= array("jpeg","jpg","png","tif");

							/*if(in_array($file_ext,$expensions)=== false){
								$errors[]="extension not allowed, please choose a JPEG or PNG file.";
							}*/

							/*if($file_size > 2097152){
								$errors[]='File size must be excately 2 MB';
							}*/

							$folder='images'.DIRECTORY_SEPARATOR.$Microscope.DIRECTORY_SEPARATOR.$ObjMagn.DIRECTORY_SEPARATOR.$ObjNA.DIRECTORY_SEPARATOR.$Date.DIRECTORY_SEPARATOR.$AU.DIRECTORY_SEPARATOR;

							if(empty($errors)==true){
								if(!file_exists($folder))
								{
									mkdir($folder,0777,true);
								}
								move_uploaded_file($file_tmp,$folder."Image.".$file_ext);
								//echo realpath(dirname(__FILE__));
								//echo "Success";
							}
							else{
								print_r($errors);
							}
						}

						//Then go the success page
						echo '<meta http-equiv="Refresh" content="0;url=success.php">';		
					//}
				}
				//If it's not a LSM
				else
				{
					//Get the theoretical values
					$query = "Select LTVx,LTVy,LTVz from $Microscope where ObjMagn=? and ROUND(ObjNA)=ROUND(?) ;";
					$stmt = $bdd->prepare($query);
					$stmt->execute(array($ObjMagn,$ObjNA));
					$Values = $stmt->fetch();

					//Get the values for each dimension
					$LTVx =$Values['LTVx'];
					$LTVy = $Values['LTVy'];
					$LTVz =$Values['LTVz'];

					if ($LTVx == "")
						$LTVx = 0;
					if ($LTVy == "")
						$LTVy = 0;
					if ($LTVz == "")
						$LTVz = 0;

					//Get the different values separated by ","
					/*$NumberBead = preg_split("/[,]+/", $NumberBead);
					$LAVx = preg_split("/[,]+/", $LAVx);
					$LAVy = preg_split("/[,]+/", $LAVy);
					$LAVz = preg_split("/[,]+/", $LAVz);*/

					//If the number of values for each field is different, then fail
					/*if ((sizeof($NumberBead) != sizeof($LAVx)) || (sizeof($NumberBead) != sizeof($LAVy)) || (sizeof($NumberBead) != sizeof($LAVz)))
					{
						echo '<meta http-equiv="Refresh" content="0;url=fail.php">';
					}
					//Else ok continue
					else
					{*/
						//If it's the CellR
						if ($Microscope == "CellR")
						{
							//For each value add it to the database by a secure request
							for ($i=0; $i<$NumberBead;$i++)
							{
								//echo "CellR";
								//echo $ActVal[$i];
								$ZXY = preg_split("/[;]+/", $ActVal[$i]);
								//echo $ZXY[0];

								$query="Insert into $Microscope (Date,NumberBead,ObjMagn,ObjNA,LTVx,LTVy,LTVz,LAVx,LAVy,LAVz,NameUser,Settings,Comment) 
								VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);";
								$stmt = $bdd->prepare($query);
								$stmt->execute(array( $Date, $i+1,
									$ObjMagn, $ObjNA, $LTVx,
									$LTVy, $LTVz, $ZXY[1],
									$ZXY[2], $ZXY[0],$NameUser, $Settings,$Comments));
							}
						}
						//If it's the AxioObserver
						else
						{
							//For each value add it to the database by a secure request
							for ($i=0; $i<$NumberBead;$i++)
							{


								//echo $ActVal[$i];
								$ZXY = preg_split("/[;]+/", $ActVal[$i]);
								//echo $ZXY[0];

								$query="Insert into $Microscope (Date,NumberBead,ObjMagn,ObjNA,LTVx,LTVy,LTVz,LAVx,LAVy,LAVz,NameUser,Settings,Comment) 
								VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);";
								//echo $query;
								//echo "X :"+$ZXY[1]+" Y : "+$ZXY[2]+" Z: "+$ZXY[0];
								$stmt = $bdd->prepare($query);
								$stmt->execute(array( $Date, $i+1,
									$ObjMagn, $ObjNA, $LTVx,
									$LTVy, $LTVz, $ZXY[1],
									$ZXY[2], $ZXY[0],$NameUser, $Settings,$Comments));
							}
						}

						if(isset($_FILES['image'])){
							$errors= array();
							$testname = "Image";
							$file_name = $_FILES['image']['name'];
							$file_size =$_FILES['image']['size'];
							$file_tmp =$_FILES['image']['tmp_name'];
							$file_type=$_FILES['image']['type'];
							$file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));

							$expensions= array("jpeg","jpg","png","tif");

							/*if(in_array($file_ext,$expensions)=== false){
								$errors[]="extension not allowed, please choose a JPEG or PNG file.";
							}*/

							/*if($file_size > 2097152){
								$errors[]='File size must be excately 2 MB';
							}*/

							if(preg_match('/LSM/i', $Microscope))
							{
								$folder='images'.DIRECTORY_SEPARATOR.$Microscope.DIRECTORY_SEPARATOR.$ObjMagn.DIRECTORY_SEPARATOR.$ObjNA.DIRECTORY_SEPARATOR.$Date.DIRECTORY_SEPARATOR.$AU.DIRECTORY_SEPARATOR;
							}
							else
							{
								$folder='images'.DIRECTORY_SEPARATOR.$Microscope.DIRECTORY_SEPARATOR.$ObjMagn.DIRECTORY_SEPARATOR.$ObjNA.DIRECTORY_SEPARATOR.$Date.DIRECTORY_SEPARATOR;
							}
			

							if(empty($errors)==true){
								if(!file_exists($folder))
								{
									mkdir($folder,0777,true);
								}
								move_uploaded_file($file_tmp,$folder."Image.".$file_ext);
								//echo realpath(dirname(__FILE__));
								//echo "Success";
							}
							else{
								print_r($errors);
							}
						}

						//Then refresh to success page
						echo '<meta http-equiv="Refresh" content="0;url=success.php">';		
					//}

				}

				

				
				//echo $test;
			//}
			//move_uploaded_file($_FILES['uploadFile']['tmp_name'], "\\\\nas.cci.sahlgrenska.gu.se\\GEMENSAM\\Test\\{$_FILES['uploadFile']['Test']}");
		}
		?>
		<footer>
			<!-- Link to the PSF page -->
			<p><i> To see the graphics : <a href="PSF.php"> this way </a></i></p>
		</footer>	

	</body>
	</html>