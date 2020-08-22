<!doctype html>
<html>

<head>
	<title>Bar Chart</title>
	<!--<script src="../../../dist/chart.min.js"></script>-->
	<script src="../../utils.js"></script>
	<style>
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	</style>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

</head>

<body>
	<?php
	//include("connection.php");
	global $DB;
	$sql="select mqg.grade,to_timestamp(mqg.timemodified),mq.name from mdl_quiz_grades mqg, mdl_quiz mq where mqg.userid = 3408
					and mq.id = mqg.quiz";
	//var_dump($sql);
    while($row=$DB->mysqli_fetch_array($sql))
	{
		  if(!empty($row['mqg.grade']))
          $allrows[]=$row['mqg.grade'];
		if(!empty($row['mq.name']))
          $allcourse[]=$row['mq.name'];
    }
	//print_r($allrows);
	$string=implode("," , $allrows);
	//print_r($string);
		//echo $temp = $row['score'].",";
	//echo "temp ".$temp;
	?>
	<div id="container" style="width: 75%;">

		<canvas id="myChart" width="400" height="200"></canvas>
			<script>
			var ctx = document.getElementById('myChart').getContext('2d');
			// Using PHP implode() function 
			var passedArray =  
				<?php echo '["' . implode('", "', $allrows) . '"]' ?>; 
				
			var courseArray =  
				<?php echo '["' . implode('", "', $allcourse) . '"]' ?>; 
			   
			// Printing the passed array elements 
			document.write(passedArray); 
			var test = passedArray;
			var testLabel = courseArray;
			console.log(courseArray);
			var myChart = new Chart(ctx, {
				type: 'bar',
				
				//var test = <?php print_r($string);?>
				//console.log(test);
				data: {
					//labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
					labels: courseArray,
					datasets: [{
						label: '# of Scores',
						//data: [12, 19, 3, 5, 2, 3],
						data: test,
						backgroundColor: [
							'rgba(255, 99, 132, 0.2)',
							'rgba(54, 162, 235, 0.2)',
							'rgba(255, 206, 86, 0.2)',
							'rgba(75, 192, 192, 0.2)',
							'rgba(153, 102, 255, 0.2)',
							'rgba(255, 159, 64, 0.2)'
						],
						borderColor: [
							'rgba(255, 99, 132, 1)',
							'rgba(54, 162, 235, 1)',
							'rgba(255, 206, 86, 1)',
							'rgba(75, 192, 192, 1)',
							'rgba(153, 102, 255, 1)',
							'rgba(255, 159, 64, 1)'
						],
						borderWidth: 1
					}]
				},
				options: {
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: true
							}
						}]
					}
				}
			});
			</script>
	</div>
	
	<button id="randomizeData">Randomize Data</button>
	<button id="addDataset">Add Dataset</button>
	<button id="removeDataset">Remove Dataset</button>
	<button id="addData">Add Data</button>
	<button id="removeData">Remove Data</button>
	<script>
		var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		var color = Chart.helpers.color;
		var barChartData = {
			labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
			datasets: [{
				label: 'Dataset 1',
				backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
				borderColor: window.chartColors.red,
				borderWidth: 1,
				data: [
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor()
				]
			}, {
				label: 'Dataset 2',
				backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
				borderColor: window.chartColors.blue,
				borderWidth: 1,
				data: [
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor()
				]
			}]

		};

		window.onload = function() {
			var ctx = document.getElementById('canvas').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					legend: {
						position: 'top',
					},
					title: {
						display: true,
						text: 'Chart.js Bar Chart'
					}
				}
			});

		};

		document.getElementById('randomizeData').addEventListener('click', function() {
			var zero = Math.random() < 0.2 ? true : false;
			barChartData.datasets.forEach(function(dataset) {
				dataset.data = dataset.data.map(function() {
					return zero ? 0.0 : randomScalingFactor();
				});

			});
			window.myBar.update();
		});

		var colorNames = Object.keys(window.chartColors);
		document.getElementById('addDataset').addEventListener('click', function() {
			var colorName = colorNames[barChartData.datasets.length % colorNames.length];
			var dsColor = window.chartColors[colorName];
			var newDataset = {
				label: 'Dataset ' + (barChartData.datasets.length + 1),
				backgroundColor: color(dsColor).alpha(0.5).rgbString(),
				borderColor: dsColor,
				borderWidth: 1,
				data: []
			};

			for (var index = 0; index < barChartData.labels.length; ++index) {
				newDataset.data.push(randomScalingFactor());
			}

			barChartData.datasets.push(newDataset);
			window.myBar.update();
		});

		document.getElementById('addData').addEventListener('click', function() {
			if (barChartData.datasets.length > 0) {
				var month = MONTHS[barChartData.labels.length % MONTHS.length];
				barChartData.labels.push(month);

				for (var index = 0; index < barChartData.datasets.length; ++index) {
					barChartData.datasets[index].data.push(randomScalingFactor());
				}

				window.myBar.update();
			}
		});

		document.getElementById('removeDataset').addEventListener('click', function() {
			barChartData.datasets.pop();
			window.myBar.update();
		});

		document.getElementById('removeData').addEventListener('click', function() {
			barChartData.labels.splice(-1, 1); // remove the label first

			barChartData.datasets.forEach(function(dataset) {
				dataset.data.pop();
			});

			window.myBar.update();
		});
	</script>
</body>

</html>
