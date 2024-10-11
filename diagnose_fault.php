<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {
	$useremail = $_SESSION['login'];
	$user_id = $_SESSION['user_id'];
	$sql = "SELECT * from tblusers where EmailId=:useremail";
	$query = $dbh->prepare($sql);
	$query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
	$query->execute();
	$results = $query->fetchAll(PDO::FETCH_OBJ);
	// $user_id = $result->id;

	// Check if the form is submitted
	if (isset($_POST["diagnose"])) {

		// Get form data
		$faults = array(
			'f1' => $_POST['f1'],
			'f2' => $_POST['f2'],
			'f3' => $_POST['f3'],
			'f4' => $_POST['f4'],
			'f5' => $_POST['f5'],
			'f6' => $_POST['f6'],
			'f7' => $_POST['f7'],
			'f8' => $_POST['f8'],
			'f9' => $_POST['f9'],
			'f10' => $_POST['f10'],
			'f11' => $_POST['f11'],
			'f12' => $_POST['f12'],
			'f13' => $_POST['f13'],
			'f14' => $_POST['f14'],
			'f15' => $_POST['f15'],
			'f16' => $_POST['f16'],
			'f17' => $_POST['f17'],
			'f18' => $_POST['f18'],
			'f19' => $_POST['f19'],
			'f20' => $_POST['f20'],
			'f21' => $_POST['f21'],
			'f22' => $_POST['f22'],
			'f23' => $_POST['f23'],
			'f24' => $_POST['f24'],
			'f25' => $_POST['f25'],
			'f26' => $_POST['f26'],
			'f27' => $_POST['f27'],
			'f28' => $_POST['f28'],
			'f29' => $_POST['f29'],
			'f30' => $_POST['f30'],
			'f31' => $_POST['f31'],
			'f32' => $_POST['f32']
		);

		// Prepare fault values for SQL IN clause
		$placeholders = implode(',', array_fill(0, count($faults), '?'));

		try {
			// Step 1: Retrieve fault diagnosis rule from the fault_diagnosis table
			$query = "SELECT diagnosed_fault, advice FROM faults_diagnosis WHERE (f1, f2, f3, f4, f5, f6, f7, f8, f9, f10, 
                f11, f12, f13, f14, f15, f16, f17, f18, f19, f20, f21, f22, f23, f24, f25, f26, f27, f28, f29, 
                f30, f31, f32) = ($placeholders)";
			$stmt = $dbh->prepare($query);
			$stmt->execute(array_values($faults));

			// Check if a fault diagnosis is found
			if ($stmt->rowCount() > 0) {
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$fault = $result['diagnosed_fault'];
				$advice = $result['advice'];

				// Step 2: Insert diagnosis into user_faults_diagnosis table
				$insert_query = "INSERT INTO users_faults_diagnosis 
                    (user_id, f1, f2, f3, f4, f5, f6, f7, f8, f9, f10, f11, f12, f13, f14, f15, f16, f17, f18, 
                    f19, f20, f21, f22, f23, f24, f25, f26, f27, f28, f29, f30, f31, f32, diagnosed_fault, advice) 
                    VALUES (:user_id, :f1, :f2, :f3, :f4, :f5, :f6, :f7, :f8, :f9, :f10, :f11, :f12, :f13, 
                    :f14, :f15, :f16, :f17, :f18, :f19, :f20, :f21, :f22, :f23, :f24, :f25, :f26, :f27, 
                    :f28, :f29, :f30, :f31, :f32, :fault, :advice)";

				$insert_stmt = $dbh->prepare($insert_query);

				// Bind parameters
				foreach ($faults as $key => $value) {
					$insert_stmt->bindParam(":$key", $value, PDO::PARAM_INT);
				}
				$insert_stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
				$insert_stmt->bindParam(':fault', $fault, PDO::PARAM_STR);
				$insert_stmt->bindParam(':advice', $advice, PDO::PARAM_STR);

				// Execute insertion query
				if ($insert_stmt->execute()) {
					// Redirect to success page displaying fault and advice
					$_SESSION['fault'] = $fault;
					$_SESSION['advice'] = $advice;
					header('Location: ./admin/diagnosis_success.php');
				} else {
					$message = "<p class='alert alert-danger'>Error: Unable to store diagnosis.</p>";
				}
			} else {
				$message = "<p class='alert alert-danger'>No matching diagnosis found for the selected faults.</p>";
			}
		} catch (PDOException $e) {
			// Handle exception
			$message = "<p class='alert alert-danger'>Error: " . $e->getMessage() . "</p>";
		}
	}


	// Close connection
	// $dbh = null;
?>
	<!DOCTYPE HTML>
	<html lang="en">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<meta name="keywords" content="">
		<meta name="description" content="">
		<title>Jincheng Diagnosis</title>
		<!--Bootstrap -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
		<!--Custome Style -->
		<link rel="stylesheet" href="assets/css/styles.css" type="text/css">
		<!--OWL Carousel slider-->
		<link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
		<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
		<!--slick-slider -->
		<link href="assets/css/slick.css" rel="stylesheet">
		<!--bootstrap-slider -->
		<link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
		<!--FontAwesome Font Style -->
		<link href="assets/css/font-awesome.min.css" rel="stylesheet">

		<!-- SWITCHER -->
		<link rel="stylesheet" id="switcher-css" type="text/css" href="assets/switcher/css/switcher.css" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all" data-default-color="true" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />

		<!-- Fav and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
		<link rel="shortcut icon" href="assets/images/favicon-icon/24x24.png">
		<!-- Google-Font-->
		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
	</head>

	<body>

		<!-- Start Switcher -->
		<?php include('includes/colorswitcher.php'); ?>
		<!-- /Switcher -->

		<!--Header-->
		<?php include('includes/header.php'); ?>
		<!--Page Header-->
		<!-- /Header -->

		<!--Page Header-->
		<section class="page-header profile_page">
			<div class="container">
				<div class="page-header_wrap">
					<div class="page-heading">
						<h1>My Diagnosis</h1>
					</div>
					<ul class="coustom-breadcrumb">
						<li><a href="#">Home</a></li>
						<li>My Diagnosis</li>
					</ul>
				</div>
			</div>
			<!-- Dark Overlay-->
			<div class="dark-overlay"></div>
		</section>
		<!-- /Page Header-->
		<div class="ts-main-content mt-5">
			<div class="content-wrapper">
				<div class="container">
					<div class="row">
						<div class="col-md-12 col-sm-12 mx-auto">
							<h5 class="uppercase underline">Diagnose </h5>

							<!-- Zero Configuration Table -->
							<div class="panel panel-default">
								<div class="panel-heading">Diagnose Bike</div>
								<div class="panel-body">
									<div class="row">
										<?= @$message; ?>
										<div class="fault_wrap">
											<div class="col-md-12 col-sm-6">
												<form action="diagnose_fault.php" method="post" name="faults">
													<!-- Fault Diagnosis Section -->
													<h4 class="text-success">Perform a Diagnosis</h4>
													<div class="row">
														<div class="col-md-4">
															<div class="form-group">
																<label>Thin white smoke emissions from the tail pipe:</label><br>
																<input type="hidden" name="f1" value="0">
																<label><input type="radio" name="f1" value="1"> Yes</label>
																<label><input type="radio" name="f1" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Bluish exhaust emissions:</label><br>
																<input type="hidden" name="f2" value="0">
																<label><input type="radio" name="f2" value="1"> Yes</label>
																<label><input type="radio" name="f2" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Low battery:</label><br>
																<input type="hidden" name="f3" value="0">
																<label><input type="radio" name="f3" value="1"> Yes</label>
																<label><input type="radio" name="f3" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Noise pollution from the exhaust manifold:</label><br>
																<input type="hidden" name="f4" value="0">
																<label><input type="radio" name="f4" value="1"> Yes</label>
																<label><input type="radio" name="f4" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Squealing noise when changing the gear:</label><br>
																<input type="hidden" name="f5" value="0">
																<label><input type="radio" name="f5" value="1"> Yes</label>
																<label><input type="radio" name="f5" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Squealing noise while driving:</label><br>
																<input type="hidden" name="f6" value="0">
																<label><input type="radio" name="f6" value="1"> Yes</label>
																<label><input type="radio" name="f6" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Overheating engine:</label><br>
																<input type="hidden" name="f7" value="0">
																<label><input type="radio" name="f7" value="1"> Yes</label>
																<label><input type="radio" name="f7" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Leaking coolant:</label><br>
																<input type="hidden" name="f8" value="0">
																<label><input type="radio" name="f8" value="1"> Yes</label>
																<label><input type="radio" name="f8" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Hard starting:</label><br>
																<input type="hidden" name="f9" value="0">
																<label><input type="radio" name="f9" value="1"> Yes</label>
																<label><input type="radio" name="f9" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Excessive fuel consumption:</label><br>
																<input type="hidden" name="f10" value="0">
																<label><input type="radio" name="f10" value="1"> Yes</label>
																<label><input type="radio" name="f10" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Rough idle:</label><br>
																<input type="hidden" name="f11" value="0">
																<label><input type="radio" name="f11" value="1"> Yes</label>
																<label><input type="radio" name="f11" value="0"> No</label>
															</div>
														</div>

														<div class="col-md-4">

															<div class="form-group">
																<label>Stalling at idle:</label><br>
																<input type="hidden" name="f12" value="0">
																<label><input type="radio" name="f12" value="1"> Yes</label>
																<label><input type="radio" name="f12" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Excessive vibration while driving:</label><br>
																<input type="hidden" name="f13" value="0">
																<label><input type="radio" name="f13" value="1"> Yes</label>
																<label><input type="radio" name="f13" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Misfiring engine:</label><br>
																<input type="hidden" name="f14" value="0">
																<label><input type="radio" name="f14" value="1"> Yes</label>
																<label><input type="radio" name="f14" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Unusual engine noise:</label><br>
																<input type="hidden" name="f15" value="0">
																<label><input type="radio" name="f15" value="1"> Yes</label>
																<label><input type="radio" name="f15" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Excessive oil consumption:</label><br>
																<input type="hidden" name="f16" value="0">
																<label><input type="radio" name="f16" value="1"> Yes</label>
																<label><input type="radio" name="f16" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Oil leak:</label><br>
																<input type="hidden" name="f17" value="0">
																<label><input type="radio" name="f17" value="1"> Yes</label>
																<label><input type="radio" name="f17" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Jerking during acceleration:</label><br>
																<input type="hidden" name="f18" value="0">
																<label><input type="radio" name="f18" value="1"> Yes</label>
																<label><input type="radio" name="f18" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Brake failure:</label><br>
																<input type="hidden" name="f19" value="0">
																<label><input type="radio" name="f19" value="1"> Yes</label>
																<label><input type="radio" name="f19" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Grinding noise when braking:</label><br>
																<input type="hidden" name="f20" value="0">
																<label><input type="radio" name="f20" value="1"> Yes</label>
																<label><input type="radio" name="f20" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Poor acceleration:</label><br>
																<input type="hidden" name="f21" value="0">
																<label><input type="radio" name="f21" value="1"> Yes</label>
																<label><input type="radio" name="f21" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Steering wheel vibrations:</label><br>
																<input type="hidden" name="f22" value="0">
																<label><input type="radio" name="f22" value="1"> Yes</label>
																<label><input type="radio" name="f22" value="0"> No</label>
															</div>

														</div>

														<div class="col-md-4">
															<div class="form-group">
																<label>Pulling to one side while driving:</label><br>
																<input type="hidden" name="f23" value="0">
																<label><input type="radio" name="f23" value="1"> Yes</label>
																<label><input type="radio" name="f23" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Check engine light:</label><br>
																<input type="hidden" name="f24" value="0">
																<label><input type="radio" name="f24" value="1"> Yes</label>
																<label><input type="radio" name="f24" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Unusual odor (burning, fuel, etc.):</label><br>
																<input type="hidden" name="f25" value="0">
																<label><input type="radio" name="f25" value="1"> Yes</label>
																<label><input type="radio" name="f25" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Low oil pressure:</label><br>
																<input type="hidden" name="f26" value="0">
																<label><input type="radio" name="f26" value="1"> Yes</label>
																<label><input type="radio" name="f26" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Battery warning light:</label><br>
																<input type="hidden" name="f27" value="0">
																<label><input type="radio" name="f27" value="1"> Yes</label>
																<label><input type="radio" name="f27" value="0"> No</label>
															</div>
															<div class="form-group">
																<label>Shaky or vibrating wheel:</label><br>
																<input type="hidden" name="f28" value="0">
																<label><input type="radio" name="f28" value="1"> Yes</label>
																<label><input type="radio" name="f28" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Fuel leak:</label><br>
																<input type="hidden" name="f29" value="0">
																<label><input type="radio" name="f29" value="1"> Yes</label>
																<label><input type="radio" name="f29" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Transmission slipping:</label><br>
																<input type="hidden" name="f30" value="0">
																<label><input type="radio" name="f30" value="1"> Yes</label>
																<label><input type="radio" name="f30" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Difficulty shifting gears:</label><br>
																<input type="hidden" name="f31" value="0">
																<label><input type="radio" name="f31" value="1"> Yes</label>
																<label><input type="radio" name="f31" value="0"> No</label>
															</div>

															<div class="form-group">
																<label>Excessive brake pedal travel:</label><br>
																<input type="hidden" name="f32" value="0">
																<label><input type="radio" name="f32" value="1"> Yes</label>
																<label><input type="radio" name="f32" value="0"> No</label>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="form-group mx-5">
															<input type="submit" value="Diagnose Fault" name="diagnose" id="submit" class="btn btn-primary h1">
														</div>
													</div>

												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>

			<!--/my-vehicles-->
			<?php include('includes/footer.php'); ?>

			<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/bootstrap.min.js"></script>
			<script src="assets/js/interface.js"></script>
			<!--Switcher-->
			<script src="assets/switcher/js/switcher.js"></script>
			<!--bootstrap-slider-JS-->
			<script src="assets/js/bootstrap-slider.min.js"></script>
			<!--Slider-JS-->
			<script src="assets/js/slick.min.js"></script>
			<script src="assets/js/owl.carousel.min.js"></script>
	</body>

	</html>
<?php } ?>