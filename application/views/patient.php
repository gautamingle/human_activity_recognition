<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Human Activity Recognition</title>

	<script src="../application/libraries/Chart.min.js"></script>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}

	.label_indicator {
	    font-size: 20px;
	}

	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
		text-align: center;
	}

	.code_titles {
		text-align: center;
		font-weight: bold;
	}

	#myChart {
		height: 400px !important;
	}
	</style>
</head>
<body>

<div id="container">
	<h1><center>Human Activity Recognition</center></h1>

	<div id="body">
		<p>
			<center>
				<input type="button" id="on_off" name="on_off" value="ON/OFF" onclick="start_har();" style="width: 10%; color: blue;">
			</center>
		</p>
		<p style="text-align: right; margin-right: 5%;">
			<label type="button" id="fall_indicator" name="fall_indicator" class="label_indicator" value="Fall Detected!" style="width: 10%; color: red;">Fall Detected!</label>
		</p>
		<code class="code_titles">Sensor Values</code>
		<center>
			<table class="sensor_value_table" style="width:80%">
				<tr>
					<th>Accelerometer - 1</th>
					<th>Accelerometer - 2</th> 
					<th>Gyroscope</th>
				</tr>
				<tr>
					<td>Acc_X = 0.000</td>
					<td>Acc_Y = -212.211</td> 
					<td>Acc_Z = -50.573</td>
				</tr>
				<tr>
					<td>Acc_X = 0.000</td>
					<td>Acc_Y = -123.211</td> 
					<td>Acc_Z = -54.573</td>
				</tr>
				<tr>
					<td>Gyr_X = 23.000</td>
					<td>Gyr_Y = -11.211</td> 
					<td>Gyr_Z = -67.573</td>
				</tr>
			</table>
			<p>Current Status = Detected fall, patient location measured!</p>
			<p>Orientation = Lying Down</p>
		</center>
		<code class="code_titles">Graph</code>
		<canvas id="myChart" height="200px;"></canvas>
	</div>
</div>

<script type="text/javascript">
	// var myLineChart = new Chart(ctx, {
	// 							    type: 'line',
	// 							    data: data,
	// 							    options: options
	// 							});


	var myLineChart = new Chart(document.getElementById("myChart"),
								{"type":"line","data":
									{
										"labels":["January","February","March","April","May","June","July"],
										"datasets":[{
													"label":"Acceleration",
													"data":[62,62,62,62,70,58,62,62,62,62,62,62],
													"fill":false,
													"borderColor":"rgb(75, 192, 192)",
													"lineTension":0.01
												}]
									},
									"options":{}
								}
							);

	// var ctx = document.getElementById("myChart");
	// var myChart = new Chart(ctx, {
	//     type: 'line',
	//     data: {
	//         labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
	//         datasets: [{
	//             label: '# of Votes',
	//             data: [12, 19, 3, 5, 2, 3],
	//             backgroundColor: [
	//                 'rgba(255, 99, 132, 0.2)',
	//                 'rgba(54, 162, 235, 0.2)',
	//                 'rgba(255, 206, 86, 0.2)',
	//                 'rgba(75, 192, 192, 0.2)',
	//                 'rgba(153, 102, 255, 0.2)',
	//                 'rgba(255, 159, 64, 0.2)'
	//             ],
	//             borderColor: [
	//                 'rgba(255,99,132,1)',
	//                 'rgba(54, 162, 235, 1)',
	//                 'rgba(255, 206, 86, 1)',
	//                 'rgba(75, 192, 192, 1)',
	//                 'rgba(153, 102, 255, 1)',
	//                 'rgba(255, 159, 64, 1)'
	//             ],
	//             borderWidth: 1
	//         }]
	//     },
	//     options: {
	//         scales: {
	//             yAxes: [{
	//                 ticks: {
	//                     beginAtZero:true
	//                 }
	//             }]
	//         }
	//     }
	// });

</script>
</body>
</html>