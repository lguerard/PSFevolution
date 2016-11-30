<!DOCTYPE html>
<html>
<head>


	<title>Evolution of PSF in time</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="Demo project">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
	<style type="text/css"></style>

	
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

	//Get the name of the tables in order to choose the microscope
	$choiceMicroscope = $bdd->query('select table_name from information_schema.tables where table_schema="PSF"');

	?>
	<form method="POST" action="PSF.php">
	<!-- Menu with the different microscopes -->
		<select id="Microscope" name="Microscope" onChange="hideshow();" onMouseDown="document.getElementById('ObjMagn').selectedIndex=0;" required>

			<option value="None">Select Microscope</option>
			<?php

			//Secure way of making a list with the different microscope and avoid SQL injections
			$table_list = array();
			$numMic = 0;
			while($choice = $choiceMicroscope->fetch())
			{
				?>
				<option value="<?php echo $numMic; ?>"><?php echo $choice['table_name']; ?></option>
				<?php
				$numMic++;
				array_push($table_list,$choice['table_name']);
			}
			?>
		</select>

		<!-- Objective magnification menu with all the different values -->
		<select id="ObjMagn" name="ObjMagn" style="display:none" onChange="hideshow();" required>
			<option value="None">Select Objective Magnification</option>
			<option value="40x">40x</option>
			<option value="60x">60x</option>
			<option value="63x">63x</option>
			<option value="100x">100x</option>
			<option value="20x">20x</option>
		</select>

		<!-- Objective NA menu with all the different values -->
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

		<!-- Airy Unit menu with all the different values -->
		<select id="AU" name="AU" style="display:none" required>
			<option value= "">Select Airy Unit</option>
			<option value= "1">1</option>
			<option value="Max">Max</option>
		</select>

		<!-- Date you want the values to be shown -->
		<br>
		Select the date from which the values will be shown. Format is YYYY-MM-DD<br>
		<input type="date" name="Date" id="Date" value="0000-00-00" />

		<br>

		Select the date until which the values will be shown. Format is YYYY-MM-DD<br>
		<input type="date" name="DateEnd" id="DateEnd" value="9999-12-31" />

		<br>

		<!-- Submit button -->
		<input type="Submit" value="Send" id="Send" name="Send" style="display:none" >
	</form>

	
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
									na[0].style.display="none";
									na[1].style.display="block";
									na[2].style.display="none";
									na.selectedIndex = 1;
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

		<script type='text/javascript'>
			//Function to draw the chart
			function drawChart()
			{
				//Table of the data
				var data = new google.visualization.DataTable();

				//Declare columns
				data.addColumn('date','Date');
				data.addColumn('number','Actual Value for X');
				data.addColumn({id:'interval', type:'number',role:'interval'});
				data.addColumn({id:'interval', type:'number',role:'interval'});
				data.addColumn('number','Actual Value for Y');
				data.addColumn({id:'interval', type:'number',role:'interval'});
				data.addColumn({id:'interval', type:'number',role:'interval'});
				data.addColumn('number','Actual Value for Z');
				data.addColumn({id:'interval', type:'number',role:'interval'});
				data.addColumn({id:'interval', type:'number',role:'interval'});
				data.addColumn('number','Theoretical Value for X and Y');
				//data.addColumn('number','Theoretical Value for y');
				data.addColumn('number','Theoretical Value for Z');

				//Add data
				<?php

				if(isset($_POST['Send']))
				{
					//Get the different values
					$Microscope = $table_list[$_POST['Microscope']];
					$ObjMagn = $_POST['ObjMagn'];
					$ObjNA = $_POST['NA'];
					$AU = $_POST['AU'];
					$Settings =$_POST['Settings'];
					$DateBegin = $_POST['Date'];
					$DateEnd = $_POST['DateEnd'];

					//echo $DateBegin;

					//If LSM, there's an added option
					if(preg_match('/LSM/i',$Microscope))
					{
						//Secured query to avoid SQL injections
						$query = "Select distinct Date from $Microscope where ObjMagn = ? and ROUND(ObjNA)=ROUND(?) and AU = ? AND Date BETWEEN ? AND ? ORDER BY Date ASC";
						$stmt = $bdd->prepare($query);
						$stmt->execute(array($ObjMagn,$ObjNA,$AU,$DateBegin,$DateEnd));

						//Get the different date corresponding to the values in the database
						$Distinctdate = $stmt->fetchAll();
						$NumberDistinctDate = count($Distinctdate);
					}
					//If it's the CellR of the AxioObserver
					else
					{		
						//Secured query, without the AU, to avoir SQL injections
						$query = "Select distinct Date from $Microscope where ObjMagn = ? and ROUND(ObjNA)=ROUND(?) AND Date BETWEEN ? AND ? ORDER BY Date ASC";
						$stmt = $bdd->prepare($query);
						$stmt->execute(array($ObjMagn,$ObjNA,$DateBegin,$DateEnd));

						//Get the different date corresponding to the values in the database
						$Distinctdate = $stmt->fetchAll();
						$NumberDistinctDate = count($Distinctdate);
					}

					//Loop through the dates
					for($i=0;$i<$NumberDistinctDate;$i++)
					{
						//If LSM then there is one value more to get in the database
						if(preg_match('/LSM/i', $Microscope))
						{
							//Secured query to get the number of bead and get the maximum and minimum
/*							$query = "Select distinct NumberBead from $Microscope where ObjMagn = ? and ROUND(ObjNA)=ROUND(?) and AU=?";
							$stmt = $bdd->prepare($query);
							$stmt->execute(array($ObjMagn,$ObjNA,$AU));
							$DistinctBead = $stmt->fetch();
							$NumberDistinctBead = count($DistinctBead);*/

							//Get the values
							$query = "Select MIN(LAVx) AS ValueMinx, MIN(LAVy) AS ValueMiny, MIN(LAVz) AS ValueMinz, MAX(LAVX) AS ValueMaxx, MAX(LAVy) AS ValueMaxy, MAX(LAVz) AS ValueMaxz, TRUNCATE(AVG(LAVx),1) AS ValueMoyx, 
									TRUNCATE(AVG(LAVy),1) AS ValueMoyy, TRUNCATE(AVG(LAVz),1) AS ValueMoyz, LTVx AS ValueLTVx, LTVy AS ValueLTVy, LTVz AS ValueLTVz 
									from $Microscope where ObjMagn=? and ROUND(ObjNA)=ROUND(?) and Date = ? and AU=?";
							$stmt = $bdd->prepare($query);
							$stmt->execute(array($ObjMagn,$ObjNA,$Distinctdate[$i]['Date'],$AU));
							$Values2 = $stmt->fetch();



						}
						//If not LSM, then we don't care about the AU
						else
						{
							//Secured query to get the number of bead and get the maximum and minimum
/*							$query = "Select distinct NumberBead from $Microscope where ObjMagn = ? and ROUND(ObjNA)=ROUND(?) ";
							$stmt = $bdd->prepare($query);
							$stmt->execute(array($ObjMagn,$ObjNA));
							$DistinctBead = $stmt->fetch();
							$NumberDistinctBead = count($DistinctBead);*/

							//Get the values
							$query = "Select MIN(LAVx) AS ValueMinx, MIN(LAVy) AS ValueMiny, MIN(LAVz) AS ValueMinz, MAX(LAVX) AS ValueMaxx, MAX(LAVy) AS ValueMaxy, MAX(LAVz) AS ValueMaxz, TRUNCATE(AVG(LAVx),1) AS ValueMoyx, 
										TRUNCATE(AVG(LAVy),1) AS ValueMoyy, TRUNCATE(AVG(LAVz),1) AS ValueMoyz, LTVx AS ValueLTVx, LTVy AS ValueLTVy, LTVz AS ValueLTVz 
										from $Microscope where ObjMagn=? and ROUND(ObjNA)=ROUND(?) and Date = ? ";

							$stmt = $bdd->prepare($query);
							$stmt->execute(array($ObjMagn,$ObjNA,$Distinctdate[$i]['Date']));
							$Values2 = $stmt->fetch();

							$folder='images'.DIRECTORY_SEPARATOR.$Microscope.DIRECTORY_SEPARATOR.$ObjMagn.DIRECTORY_SEPARATOR.$ObjNA.DIRECTORY_SEPARATOR.$Date.DIRECTORY_SEPARATOR;

						}
		
				?>
				//We add the values in the data table for each date
					data.addRows([
						[new Date("<?php echo $Distinctdate[$i]['Date']; ?>"),
						<?php echo $Values2['ValueMoyx']; ?>,
						<?php echo $Values2['ValueMinx']; ?>,
						<?php echo $Values2['ValueMaxx']; ?>,
						<?php echo $Values2['ValueMoyy']; ?>,
						<?php echo $Values2['ValueMiny']; ?>,
						<?php echo $Values2['ValueMaxy']; ?>,
						<?php echo $Values2['ValueMoyz']; ?>,
						<?php echo $Values2['ValueMinz']; ?>,
						<?php echo $Values2['ValueMaxz']; ?>,
						<?php echo $Values2['ValueLTVx']; ?>,
						//<?php echo $Values2['ValueLTVy']; ?>,
						<?php echo $Values2['ValueLTVz']; ?>
							]
							]);
				<?php
					}

				}
				
				?>
				
				      // Instantiate and draw our chart, passing in some options.
				      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

				      // create columns array
				      var columns = [0];
				      /* the series map is an array of data series
				        * "column" is the index of the data column to use for the series
				        * "roleColumns" is an array of column indices corresponding to columns with roles that are associated with this data series
				        * "display" is a boolean, set to true to make the series visible on the initial draw
				        */
				        var seriesMap = [{
				        	column: 1,
				        	roleColumns: [2,3],
				        	display: true
				        }, {
				        	column: 4,
				        	roleColumns: [5,6],
				        	display: true
				        }, {
				        	column: 7,
				        	roleColumns: [8,9],
				        	display: true
				        },
				        {
				        	column: 10,
				        	roleColumns: [],
				        	display: true
				        },
				        {
				        	column: 11,
				        	roleColumns: [],
				        	display: true
				        }/*,
				        {
				        	column: 12,
				        	roleColumns: [],
				        	display: false
				        }*/];
				        var columnsMap = {};
				        var series = [];
				        var series = {};
				        for (var i = 0; i < seriesMap.length; i++) {
				        	var col = seriesMap[i].column;
				        	columnsMap[col]=i;
				      	//Set the default series option
				      	series[i] = {};
				      	if (seriesMap[i].display) {
				              // if the column is the domain column or in the default list, display the series
				              columns.push(col);
				          }
				          else {
				              // otherwise, hide it
				              columns.push({
				              	label: data.getColumnLabel(col),
				              	type: data.getColumnType(col),
				              	sourceColumn: col,
				              	calc: function () {
				              		return null;
				              	}
				              });
				         /* if (i > 0) {
				          	columns.push({
				          		calc: 'stringify',
				          		sourceColumn: i,
				          		type: 'string',
				          		role: 'annotation'
				          	}); */
				              // set the default series option
				              if (typeof(series[i].color) !== 'undefined') {
				              	series[i].backupColor = series[i].color;
				              }
				              series[i].color = '#CCCCCC';
				          }
				          for (var j = 0; j < seriesMap[i].roleColumns.length; j++) {
				          	columns.push(seriesMap[i].roleColumns[j]);
				          }
				      }
				      
				      var options = {
				      	title : "<?php 
				      	echo 'Microscope : ';
				      	echo $Microscope; 
				      	echo ' , ObjMagn : ';
				      	echo $ObjMagn; 
				      	if(preg_match('/LSM/i', $Microscope))
				      	{
				      		echo ' , AU : ';
				      		echo $AU;
				      	}
				      	echo ' , ObjNA : ';
				      	echo $ObjNA;
				      	?>",
				      	width: '100%',
				      	height: '100%',
				      	series: series,
				      	pointSize: 5
				      }
				      
				      function showHideSeries () {
				      	var sel = chart.getSelection();
				          // if selection length is 0, we deselected an element
				          if (sel.length > 0) {
				              // if row is undefined, we clicked on the legend
				              if (sel[0].row === null) {
				              	var col = sel[0].column;
				              	if (typeof(columns[col]) == 'number') {
				              		var src = columns[col];
				              		
				                      // hide the data series
				                      columns[col] = {
				                      	label: data.getColumnLabel(src),
				                      	type: data.getColumnType(src),
				                      	sourceColumn: src,
				                      	calc: function () {
				                      		return null;
				                      	}
				                      };
				                      
				                      // grey out the legend entry
				                      series[columnsMap[src]].color = '#CCCCCC';
				                  }
				                  else {
				                  	var src = columns[col].sourceColumn;
				                  	
				                      // show the data series
				                      columns[col] = src;
				                      series[columnsMap[src]].color = null;
				                  }
				                  var view = new google.visualization.DataView(data);
				                  view.setColumns(columns);
				                  chart.draw(view, options);
				              }
				          }
				      }
				      
				      google.visualization.events.addListener(chart, 'select', showHideSeries);
				      
				      // create a view with the default columns
				      var view = new google.visualization.DataView(data);
				      view.setColumns(columns);
				      chart.draw(view, options);
				  } 

			// Load the Visualization API and the piechart package.
			<?php
			if(isset($_POST['Send']))
			{
				
				?>
				google.load('visualization', '1', {'packages':['corechart']});

			// Set a callback to run when the Google Visualization API is loaded.
			google.setOnLoadCallback(drawChart);
			<?php
		}
		?>
		</script>

<div id='chart_div' style='width:1000x; height:800px'></div>

<?php
//If a selection has been made, then we show the different dates and the settings
if(isset($_POST['Send']))
{
	$folder='images'.DIRECTORY_SEPARATOR.$Microscope.DIRECTORY_SEPARATOR.$ObjMagn.DIRECTORY_SEPARATOR.$ObjNA.DIRECTORY_SEPARATOR.$Date.DIRECTORY_SEPARATOR;

	//Loop through the date
	for($i=0;$i<$NumberDistinctDate;$i++)
	{
		//If LSM
		if(preg_match('/LSM/i', $Microscope))
		{
			$query = "Select * from $Microscope where ObjMagn=? and ROUND(ObjNA)=ROUND(?) and Date = ? and AU = ?;";
			$stmt = $bdd->prepare($query);
			$stmt->execute(array($ObjMagn,$ObjNA,$Distinctdate[$i]['Date'],$AU));
			$Values = $stmt->fetchAll();
		}
		//If not
		else
		{
			$query = "Select * from $Microscope where ObjMagn=? and ROUND(ObjNA)=ROUND(?) and Date = ?;";
			$stmt = $bdd->prepare($query);
			$stmt->execute(array($ObjMagn,$ObjNA,$Distinctdate[$i]['Date']));
			$Values = $stmt->fetchAll();
		}

		//If there are settings, we show the date and then the settings
		if($Values[0]['Settings'] != "")
		{
			echo nl2br("&nbsp;Date : '<b>".$Values[0]['Date']."</b>', User : <b>".$Values[0]['NameUser']."</b>\r\n");
			echo nl2br("<pre><b>Settings: </b>".$Values[0]['Settings']."\n");
			echo nl2br("<b>Comments: </b>".$Values[0]['Comment']."\n");

			if(preg_match('/LSM/i', $Microscope))
			{
				$folder='images'.DIRECTORY_SEPARATOR.$Microscope.DIRECTORY_SEPARATOR.$ObjMagn.DIRECTORY_SEPARATOR.$ObjNA.DIRECTORY_SEPARATOR.$Values[0]['Date'].DIRECTORY_SEPARATOR.$AU.DIRECTORY_SEPARATOR;
				//echo $folder;
				if(file_exists($folder."Image.tif"))
				{
					?>
<a href="<?php $link = $folder.'Image.tif'; echo $link;?>" target="_blank" download="<?php echo $Microscope.$ObjMagn.$ObjNA.$Values[0]['Date'].$AU.'.tif';?>">Link to image</a>
				<?php
				}
				echo "</pre>";
			}
			else
			{
				$folder='images'.DIRECTORY_SEPARATOR.$Microscope.DIRECTORY_SEPARATOR.$ObjMagn.DIRECTORY_SEPARATOR.$ObjNA.DIRECTORY_SEPARATOR.$Values[0]['Date'].DIRECTORY_SEPARATOR;
				//echo $folder;
				if(file_exists($folder."Image.tif"))
				{
					?>
<a href="<?php $link = $folder.'Image.tif'; echo $link;?>" target="_blank" download="<?php echo $Microscope.$ObjMagn.$ObjNA.$Values[0]['Date'].'.tif';?>">Link to image</a>
					<?php
				}
					echo "</pre>";
			}
		}
		//If no settings then we don't show it
		else
		{
			echo nl2br("&nbsp;Date : '<b>".$Values[0]['Date']."</b>', User : <b>".$Values[0]['NameUser']."</b>\r\n");
			echo nl2br("<pre><i> No settings in the database</i>\n");
			echo nl2br("<b>Comments: </b>".$Values[0]['Comment']."\n");

			if(preg_match('/LSM/i', $Microscope))
			{
				$folder='images'.DIRECTORY_SEPARATOR.$Microscope.DIRECTORY_SEPARATOR.$ObjMagn.DIRECTORY_SEPARATOR.$ObjNA.DIRECTORY_SEPARATOR.$Values[0]['Date'].DIRECTORY_SEPARATOR.$AU.DIRECTORY_SEPARATOR;
				//echo $folder;
				//echo $folder."Image.tif";
				//echo file_exists($folder."Image.tif");
				if(file_exists($folder."Image.tif"))
				{
					?>
<a href="<?php $link = $folder.'Image.tif'; echo $link;?>" target="_blank" download="<?php echo $Microscope.$ObjMagn.$ObjNA.$Values[0]['Date'].$AU.'.tif';?>">Link to image</a>
				<?php
				}
				echo "</pre>";
			}
			else
			{
				$folder='images'.DIRECTORY_SEPARATOR.$Microscope.DIRECTORY_SEPARATOR.$ObjMagn.DIRECTORY_SEPARATOR.$ObjNA.DIRECTORY_SEPARATOR.$Values[0]['Date'].DIRECTORY_SEPARATOR;
				//echo $folder;
				//echo $folder."Image.tif";
				//echo file_exists($folder."Image.tif");
				if(file_exists($folder."Image.tif"))
				{
					?>
<a href="<?php $link = $folder.'Image.tif'; echo $link;?>" target="_blank" download="<?php echo $Microscope.$ObjMagn.$ObjNA.$Values[0]['Date'].'.tif';?>">Link to image</a>			
					<?php
				}
					echo "</pre>";
			}


		}

	}

	
}
?>

<footer>
	<!-- Link to add new values to the database -->
	<p><i> To add new values : <a href="add.php"> this way </a></i></p>
</footer>

</body>
</html>