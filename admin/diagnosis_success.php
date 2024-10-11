<?php
session_start();
include('includes/config.php');

$user_session = $_SESSION['login'];
$admin_session = $_SESSION['alogin'];

// Ensure the session data is available
if (!isset($_SESSION['user_id']) || !isset($_SESSION['fault']) || !isset($_SESSION['advice'])) {
    echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
    exit;
}

$user_id = $_SESSION['user_id'];
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

    <title>Diagnosis Success</title>

    <!-- Font awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Sandstone Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Admin Style -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="ts-main-content">
        <?php include('includes/leftbar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title text-primary">Diagnosis Successful</h2>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Diagnosis Results</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Diagnosed Fault:</h4>
                                            <h3 class="text-primary">
                                                <span class="text-danger"><?= $_SESSION['fault']; ?></span>
                                            </h3>

                                            <br><br>
                                            <!-- Button to open the modal for diagnosis advice -->
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#diagnosisAdviceModal">
                                                View Diagnosis Advice
                                            </button>
                                            <!-- Button to perform another diagnosis -->
                                            <a id="diagnoseBtn" class="btn btn-primary">Perform Another Diagnosis</a>
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

    <!-- Modal for displaying diagnosis advice -->
    <div class="modal fade" id="diagnosisAdviceModal" tabindex="-1" role="dialog" aria-labelledby="diagnosisAdviceLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="diagnosisAdviceLabel">Diagnosis Advice</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Advice:</h4>
                    <h3 class="text-success"><?= $_SESSION['advice']; ?></h3>
                </div>
                <div class="modal-footer">
                    <!-- Button to return to success page, which just closes the modal -->
                    <button type="button" class="btn btn-success" data-dismiss="modal">Return to Success Page</button>
                    <!-- Button to perform another diagnosis -->
                    <a id="modalDiagnoseBtn" class="btn btn-primary">Perform Another Diagnosis</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript">
        // Redirect based on session type for the 'Perform Another Diagnosis' button
        document.getElementById('diagnoseBtn').onclick = function() {
            <?php if ($user_session): ?>
                window.location.href = '../diagnose_fault.php';
            <?php elseif ($admin_session): ?>
                window.location.href = 'diagnose.php';
            <?php endif; ?>
        };

        // Also apply the same logic for the button inside the modal
        document.getElementById('modalDiagnoseBtn').onclick = function() {
            <?php if ($user_session): ?>
                window.location.href = '../diagnose_fault.php';
            <?php elseif ($admin_session): ?>
                window.location.href = 'diagnose.php';
            <?php endif; ?>
        };
    </script>
</body>

</html>