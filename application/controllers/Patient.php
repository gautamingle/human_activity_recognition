<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient extends CI_controller {

	public function __construct()
    {
        parent::__construct();
    }

	/**
	 * Patient - Detect falls for patient
	 *
	 *
	 */
	public function index()
	{
		$this->load->view('patient');
	}

	/**
	 * Import CSV data into system
	 *
	 *
	 */
	public function import_csv()
	{
		$sensor_data_a1 = NULL;
		$sensor_data_g = NULL;
		$sensor_data_a2 = NULL;

		$file_name = basename($_FILES["sensor_data"]["name"]);
		$sensor_data_file = APPPATH.'temp/'. $file_name;

		$ext_header = pathinfo($file_name, PATHINFO_EXTENSION);
		if ($ext_header != "csv" && $ext_header != "txt") {
			// Upload correct file format
			echo "File not csv";
			return;
		} else {
			if (move_uploaded_file($_FILES['sensor_data']['tmp_name'], $sensor_data_file)) {
				$file = fopen($sensor_data_file, "r") or die("Problem open file");
				$file_size = filesize($sensor_data_file);

				if (!$file_size) {
					// File empty
					echo "File seems to be empty";
					return;
				}
				$csv_content = fread($file, $file_size);
				fclose($file);

				$line_separator = "\n";
				$csv_line_content = array();
				$row = 1;
				$count = 0;
				foreach (explode($line_separator, $csv_content) as $line) {
					$line = trim($line, " \t");
					$line = str_replace("\r", "", $line);
					$csv_line_content = str_getcsv($line, ",", "\""); // (line, field separator, line)

					if (!empty($csv_line_content) && count($csv_line_content) > 1) { // line should not be empty; else skip to next line
						if ($row == 1) {
							$row++;
							continue;
						}

						// Accleration - 1
						$sensor_data_a1[$count]['x'] = $this->signal_preprocessing(1, $csv_line_content[0]);
						$sensor_data_a1[$count]['y'] = $this->signal_preprocessing(1, $csv_line_content[1]);
						$sensor_data_a1[$count]['z'] = $this->signal_preprocessing(1, $csv_line_content[2]);

						// Gyro
						$sensor_data_g[$count]['x'] = $this->signal_preprocessing(2, $csv_line_content[3]);
						$sensor_data_g[$count]['y'] = $this->signal_preprocessing(2, $csv_line_content[4]);
						$sensor_data_g[$count]['z'] = $this->signal_preprocessing(2, $csv_line_content[5]);

						// Accleration - 2
						$sensor_data_a2[$count]['x'] = $this->signal_preprocessing(3, $csv_line_content[6]);
						$sensor_data_a2[$count]['y'] = $this->signal_preprocessing(3, $csv_line_content[7]);
						$sensor_data_a2[$count]['z'] = $this->signal_preprocessing(3, $csv_line_content[8]);
						$count++;
					}
				}
			} else {
				// Filesystem permission issue
				echo "Filesystem permission issue!";
				return;
			}
		}
		$this->process_overall_acceleration($sensor_data_a1, $sensor_data_g, $sensor_data_a2);
	}

	/**
	 * Process signal -> Convert from bits to G
	 *
	 *
	 */
	public function signal_preprocessing($sensor_type, $sensor_value) {
		if($sensor_type == 1) {
			// Accleration - 1
			// Acceleration [g]: [(2*Range)/(2^Resolution)]*AD

			// ADXL345:
			// Resolution: 13 bits
			// Range: +-16g

			$range_acc1 = (int)32; // +- 16G
			$resolution_acc1 = (int)13; // 13 bits
			return (((2*$range_acc1)/(pow(2,$resolution_acc1)))*$sensor_value);
		} else if($sensor_type == 2) {
			// Gyroscope
			// Angular velocity [°/s]: [(2*Range)/(2^Resolution)]*RD

			// ITG3200
			// Resolution: 16 bits
			// Range: +-2000°/s

			$range_gyro = (int)4000; // +- 2000
			$resolution_gyro = (int)16; // 16 bits
			return (((2*$range_gyro)/(pow(2,$resolution_gyro)))*$sensor_value);
		} else if($sensor_type == 3) {
			// Accleration - 2
			// Acceleration [g]: [(2*Range)/(2^Resolution)]*AD

			// MMA8451Q:
			// Resolution: 14 bits
			// Range: +-8g

			$range_acc2 = (int)16; // +- 8G
			$resolution_acc2 = (int)14; // 14 bits
			return (((2*$range_acc2)/(pow(2,$resolution_acc2)))*$sensor_value);
		} else {
			return 0;
		}
	}

	/**
	 * Acceleration
	 * A1 and A2 sensor => x,y,z axis convert to overall accleration
	 * Gyro sensor => Convert to overall angular velocity
	 */
	public function process_overall_acceleration($sensor_data_a1, $sensor_data_g, $sensor_data_a2)
	{
		// Sensor frequency sample is 200 HZ, i.e, 5ms interval between sensor data
		$timestamp = (float)0.000;
		$sensor_sampling_frequency = 0.005; // in seconds - 5 ms

		$final = array();
		$x_graph = array();
		$y_graph = array();

		// Overall accln and angular velocity calculations
		$data_count = count($sensor_data_a1); // taking only a1 as both sensor sets have same sampling freq
		for ($i=0; $i < $data_count; $i++) { 
			$a1 = sqrt(pow(2,$sensor_data_a1[$i][x]) + pow(2,$sensor_data_a1[$i][y]) + pow(2,$sensor_data_a1[$i][z]));
			$a2 = sqrt(pow(2,$sensor_data_a2[$i][x]) + pow(2,$sensor_data_a2[$i][y]) + pow(2,$sensor_data_a2[$i][z]));
			$acceleration = ($a1 + $a2)/2;

			// Taking avg of the 2 sensors so as to improve accuracy of acceleration
			$angular_velocity = sqrt(pow(2,$sensor_data_g[$i][x]) + pow(2,$sensor_data_g[$i][y]) + pow(2,$sensor_data_g[$i][z]));

			$angular_orientation = acos(($sensor_data_g[$i][z]/$angular_velocity));

			$timestamp = $timestamp + $sensor_sampling_frequency;
			$timestamp = (float) number_format((float)$timestamp, 3, '.', '');
			if($i == 0) {
				$timestamp = (float)0.000;
			}
			$temp_array = array( 
								'accln' 	=> $acceleration,
								'stamp'		=> $timestamp,
								'velocity' 	=> $angular_orientation
							);
			array_push($x_graph, number_format($acceleration, 2, '.', ''));
			array_push($y_graph, number_format($timestamp, 2, '.', ''));
			array_push($final, $temp_array);
		}
		$this->fall_detection($final, $x_graph, $y_graph, $sensor_sampling_frequency, $i);
	}

	/**
	 * Fall and Threshold
	 *
	 *
	 */
	public function fall_detection($final, $x_graph, $y_graph, $sensor_sampling_frequency, $samples)
	{
		// Window based acceleration detection

		$max_g_threshold = (float)2.5; // Based on papers & dataset
		$view_data['fall_detected_flag'] = FALSE; // Fall detection flag

		// Create a sec window
		$window_size = 0.500; // in secs
		$window_no = $window_size/$sensor_sampling_frequency; // no of samples in the window

		// Increment the window by 250 ms
		$window_increment_size = 0.250; // secs
		$window_increment_no = $window_increment_size/$sensor_sampling_frequency; // no of samples tobe incremented in each window

		$runs = ceil($samples/$window_no); // total runs for window

		$view_data['location'] = "-";
		$view_data['result'] = "No Fall";
		$view_data['max'] = 0;
		$view_data['min'] = 0;
		$max_acceleration = 0;
		$min_acceleration = 0;

		// Loop through the samples within the window
		for ($i=0; $i < $runs; $i++) { 
			// Sliding the window with time
			if($i == 0) {
				$left = (int) 0;
				$right = (int) $window_no;
			} else {
				$left = (int) $left+$window_increment_no;
				$right = (int) $right+$window_increment_no;
			}

			// Analysing samples only with the window
			$window_array = array_slice($final, $left, $right);

			// Determining max and min G's within window
			$max_key = max($window_array);
			$min_key = min($window_array);

			// Determining difference between max & min G & time
			$diff_g = abs($max_key['accln'] - $min_key['accln']);
			$diff_time = abs($max_key['stamp'] - $min_key['stamp']);

			// Tmax-Tmin > G threshold and Tmax occured after Tmin
			if ($diff_g > $max_g_threshold && $diff_time > 0) {
				$max_acceleration = $max_key['accln'];
				$min_acceleration = $min_key['accln'];
				$view_data['fall_detected_flag'] = TRUE;
			}
		}

		if($view_data['fall_detected_flag']) {
			$view_data['location'] = $this->emergency_system(); // EM call
			$view_data['result'] = "Fall Detected! Emergency services alerted with your location!";
			$view_data['max'] = number_format((float)$max_acceleration, 2, '.', '');
			$view_data['min'] = number_format((float)$min_acceleration, 2, '.', '');
		}

		// Plot acceleration graph
		$view_data['x_graph'] = implode( ", ", $x_graph);
		$view_data['y_graph'] = implode( ", ", $y_graph);

		$this->load->view('patient', $view_data);
	}

	/**
	 * Contacting emergency services
	 *
	 *
	 */
	public function emergency_system()
	{
		// Determin GPS location of patient
		// Make a call to the EM services
		return "50.116667, 8.683333";
	}
}