<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {

	// Check for deletion action
	if (isset($_GET['del'])) {
		$id = $_GET['del'];
		$sql = "DELETE FROM users_faults_diagnosis WHERE id = :id";
		$query = $dbh->prepare($sql);
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->execute();
		$message = "<div class='succWrap'>Record deleted successfully.</div>";
	}

?>

	<!doctype html>
	<html lang="en" class="no-js">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="theme-color" content="#3e454c">

		<title>Jincheng Bike Fault Diagnosis | Admin </title>

		<!-- Font awesome -->
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<!-- Sandstone Bootstrap CSS -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<!-- Bootstrap Datatables -->
		<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
		<!-- Bootstrap social button library -->
		<link rel="stylesheet" href="css/bootstrap-social.css">
		<!-- Bootstrap select -->
		<link rel="stylesheet" href="css/bootstrap-select.css">
		<!-- Bootstrap file input -->
		<link rel="stylesheet" href="css/fileinput.min.css">
		<!-- Awesome Bootstrap checkbox -->
		<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
		<!-- Admin Stye -->
		<link rel="stylesheet" href="css/style.css">
		<style>
			.errorWrap {
				padding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #dd3d36;
				-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}

			.succWrap {
				padding: 10px;
				margin: 0 0 20px 0;
				background: #fff;
				border-left: 4px solid #5cb85c;
				-webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
			}
		</style>

	</head>

	<body>
		<?php include('includes/header.php'); ?>

		<div class="ts-main-content">
			<?php include('includes/leftbar.php'); ?>
			<div class="content-wrapper">
				<div class="container-fluid">

					<div class="row">
						<div class="col-md-12">
							<h2 class="page-title text-primary">View Diagnosis</h2>

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
															<th>User</th>
															<th>Contact</th>
															<th>Date</th>
															<th>Fault</th>
															<th>Advice</th>
															<th>Action</th>
														</tr>
													</thead>
													<tfoot>
														<tr>
															<th>#</th>
															<th>User</th>
															<th>Contact</th>
															<th>Date</th>
															<th>Fault</th>
															<th>Advice</th>
															<th>Action</th>
														</tr>
													</tfoot>
													<tbody>

														<?php
														$sql = "SELECT u.id, u.FullName, u.ContactNo, d.submission_time, d.diagnosed_fault, d.advice
														FROM users_faults_diagnosis d 
														JOIN tblUsers u ON d.user_id = u.id";
														$query = $dbh->prepare($sql);
														$query->execute();
														$results = $query->fetchAll(PDO::FETCH_OBJ);
														$cnt = 1;
														if ($query->rowCount() > 0) {
															foreach ($results as $result) { ?>
																<tr>
																	<td><?php echo htmlentities($cnt); ?></td>
																	<td><?php echo htmlentities($result->FullName); ?></td>
																	<td><?php echo htmlentities($result->ContactNo); ?></td>
																	<td><?php echo htmlentities($result->submission_time); ?></td>
																	<td><?php echo htmlentities($result->diagnosed_fault); ?></td>
																	<td><?php echo htmlentities($result->advice); ?></td>
																	<td>
																		<a href="view_diagnosis.php?id=<?php echo $result->id; ?>"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;
																		<a href="view_diagnosis.php?del=<?php echo $result->id; ?>" onclick="return confirm('Do you want to delete this record?');"><i class="fa fa-close"></i></a>
																	</td>
																</tr>
														<?php
																$cnt++;
															}
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

		<!-- Loading Scripts -->
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap-select.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.dataTables.min.js"></script>
		<script src="js/dataTables.bootstrap.min.js"></script>
		<script src="js/Chart.min.js"></script>
		<script src="js/fileinput.js"></script>
		<script src="js/chartData.js"></script>
		<script src="js/main.js"></script>
	</body>

	</html>
<?php } ?>