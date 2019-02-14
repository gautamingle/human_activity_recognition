<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<base href="<?php echo base_url();?>" target="_self">
		<title>Human Activity Recognition</title>

		<script src="<?php echo base_url();?>application/libraries/jquery.min.3.3.1.js"></script>
		<script src="<?php echo base_url();?>application/libraries/Chart.min.js"></script>
		<script src="<?php echo base_url();?>application/libraries/jquery.min.3.3.1.js"></script>

		<style type="text/css">
			::selection { background-color: #E13300; color: white; }
			::-moz-selection { background-color: #E13300; color: white; }

			body {
				background-color: #FFF;
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
			    font-size: 22px;
			    font-weight: bold;
			}

			table, th, td {
				border: 1px solid black;
				border-collapse: collapse;
				text-align: center;
				margin: 5px;
			}
		</style>
	</head>
	<body>
		<div id="container">
			<h1><center>Human Activity Recognition</center></h1>
			<?php
			if(!isset($fall_detected_flag)) {
			?>
				<div id="body">
					<?php
						$attributes = array("id" => "sensor_data_form" , "name" => "sensor_data_form");
						echo form_open_multipart("patient/import_csv", $attributes);
					?>
						<input type="file" id="sensor_data" name="sensor_data" style="display: none">
					</form>
					<p>
						<center>
							<input type="button" id="submit_button" name="submit_button" value="Import" onclick="import_csv();" style="width: 20%; color: blue;">
						</center>
					</p>
				</div>
			<?php
			} else {
			?>
				<div id="body">
					<div style="text-align: center;">
						<label type="button" class="label_indicator"><?php echo $result ?></label>
						<br><br>
						<center>
							<table>
								<thead>
									<th>Max G</th>
									<th>Min G</th>
									<th>GPS Coordinates</th>
								</thead>
								<tbody>
									<td><?php echo $max ?></td>
									<td><?php echo $min ?></td>
									<td><?php echo $location ?></td>
								</tbody>
							</table>
						</center>
					</div>
					<div style="text-align: center;">
						<a style="float: right;" href="<?php echo base_url();?>index.php/patient" >Back</a>
					</div>
					<canvas id="line_graph" height="100px;"></canvas>
				</div>
			<?php
			}
			?>
		</div>

		<script type="text/javascript">
			function import_csv() {
				$("#sensor_data").trigger('click');
				$('#sensor_data').change(function() {
					$('form#sensor_data_form').submit();
				});
			}

			var ctx = document.getElementById('line_graph').getContext('2d');
			var chart = new Chart(ctx, {
			    type: 'line',
			    data: {
			        labels: [<?php echo $y_graph ?>],
			        datasets: [{
			            label: "Acceleration",
			            backgroundColor: 'lightblue',//'rgb(255, 99, 99)',
			            borderColor: 'lightblue',//'rgb(255, 99, 99)',
			            data: [<?php echo $x_graph ?>],
			        }]
			    },

			    // Configuration options go here
			    options: {}
			});
		</script>
	</body>
</html>