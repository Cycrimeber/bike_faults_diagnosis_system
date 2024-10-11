<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
	header('location:index.php');
} else {

	// Check for deletion action
	if (isset($_GET['del'])) {
		$id = $_GET['del'];
		$sql = "DELETE FROM users_faults_diagnosis WHERE id = :id";
		$query = $dbh->prepare($sql);
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->execute();
		$message = "<div class='alert alert-success succWrap'>Record deleted successfully.</div>";
	}
?>
	<!DOCTYPE HTML>
	<html lang="en">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<meta name="keywords" content="">
		<meta name="description" content="">
		<title>BikeForYou - Responsive Bike Dealer HTML5 Template</title>
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

		<?php
		$useremail = $_SESSION['login'];
		$sql = "SELECT * from tblusers where EmailId=:useremail";
		$query = $dbh->prepare($sql);
		$query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_OBJ);
		$cnt = 1;
		if ($query->rowCount() > 0) {
			foreach ($results as $result) { ?>
				<section class="user_profile inner_pages">
					<div class="container">
						<div class="user_profile_info gray-bg padding_4x4_40">
							<div class="upload_user_logo"> <img src="assets/images/dealer-logo.jpg" alt="image">
							</div>

							<div class="dealer_info">
								<h5><?php echo htmlentities($result->FullName); ?></h5>
								<p><?php echo htmlentities($result->Address); ?><br>
									<?php echo htmlentities($result->City); ?>&nbsp;<?php echo htmlentities($result->Country);
																				}
																			} ?></p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 col-sm-3">
								<?php include('includes/sidebar.php'); ?>

								<div class="col-md-6 col-sm-8">
									<div class="profile_wrap">
										<h5 class="uppercase underline">My Diagnosis </h5>
										<div class="my_vehicles_list">

											<!-- Zero Configuration Table -->
											<div class="panel panel-default">
												<div class="panel-heading">Faults Diagnosed</div>
												<div class="panel-body">
													<div class="row">
														<?= @$message; ?>
														<div class="fault_wrap">
															<div class="col-md-12 col-sm-6">
																<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
																	<thead>
																		<tr>
																			<th>#</th>
																			<th>Date</th>
																			<th>Fault</th>
																			<th>Advice</th>
																			<th>Action</th>
																		</tr>
																	</thead>
																	<tfoot>
																		<tr>
																			<th>#</th>
																			<th>Date</th>
																			<th>Fault</th>
																			<th>Advice</th>
																			<th>Action</th>
																		</tr>
																	</tfoot>
																	<tbody>
																		<?php
																		// Fetch user's diagnosed faults
																		$user_id = $result->id;
																		$sql = "SELECT * FROM users_faults_diagnosis WHERE user_id = :user_id";
																		$query = $dbh->prepare($sql);
																		$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
																		$query->execute();
																		$results = $query->fetchAll(PDO::FETCH_OBJ);
																		$cnt = 1;

																		if ($query->rowCount() > 0) {
																			foreach ($results as $result) {
																		?>
																				<tr>
																					<td><?php echo htmlentities($cnt); ?></td>
																					<td><?php echo htmlentities($result->submission_time); ?></td>
																					<td><?php echo htmlentities($result->diagnosed_fault); ?></td>
																					<td><?php echo htmlentities($result->advice); ?></td>
																					<td class="text-center">
																						<a href="my_diagnosis.php?del=<?php echo $result->id; ?>" onclick="return confirm('Do you want to delete this record?');"><i class="fa fa-trash"></i></a>
																					</td>
																				</tr>
																		<?php
																				$cnt++;
																			}
																		} else {
																			echo "<tr><td colspan='5' style='color:red;'>No records found.</td></tr>";
																		}
																		?>

																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>



										</div>
									</div>
								</div>
							</div>
						</div>
				</section>
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