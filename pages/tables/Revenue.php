<?php require_once('../../Connection.php');?>
<?php session_start(); ?>
<?php
function isPageActive($page) {
    // Get the current page filename
    $currentPage = basename($_SERVER['PHP_SELF']);
    
    // Check if the current page matches the specified page
    return $currentPage === $page ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Revenue</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>
<?php require('../../Navbar.php'); ?>
<?php require('../../Aside.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
			<div class="col-sm-6">
		  <h2><b>MISC</b></h2>
          </div><!-- /.col -->
        </div>
      </div><!-- /.container-fluid -->
    </section>

	

   <!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4 col-6">
        <!-- small card -->
        <div class="small-box bg-info">
          <div class="inner">
            <?php
            // Database connection
            $conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to calculate the total payment price from the invoice table
            $sql = "SELECT SUM(paymentprice) AS total_revenue FROM invoice";
            $result = $conn->query($sql);

            // Check if there is a result
            if ($result->num_rows > 0) {
                // Fetch the row containing the total revenue
                $row = $result->fetch_assoc();
                $totalRevenue = $row['total_revenue'];
            } else {
                $totalRevenue = 0;
            }

            // Close the database connection
            $conn->close();
            ?>

            <h3><?php echo $totalRevenue; ?></h3>
            <p>Total Revenue</p>
          </div>
          <div class="icon">
			  <i class="fas fa-money-bill-wave"></i>
			</div>
          <a href="#" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
	  
	  <div class="col-lg-4 col-6">
        <!-- small card -->
        <div class="small-box bg-info">
          <div class="inner">
            <?php
            // Database connection
            $conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to calculate the total payment price from the invoice table
            $sql = "SELECT SUM(tenantoccupied) AS total_tenants FROM rooms";
            $result = $conn->query($sql);

            // Check if there is a result
            if ($result->num_rows > 0) {
                // Fetch the row containing the total revenue
                $row = $result->fetch_assoc();
                $totalRevenue = $row['total_tenants'];
            } else {
                $totalRevenue = 0;
            }

            // Close the database connection
            $conn->close();
            ?>

            <h3><?php echo $totalRevenue; ?></h3>
            <p>Total User</p>
          </div>
          <div class="icon">
            <i class="fas fa-user"></i>
          </div>
          <a href="#" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
	  
	 <div class="col-lg-4 col-6">
    <!-- small card -->
    <div class="small-box bg-info">
        <div class="inner">
            <?php
            // Database connection
            $conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to calculate the total number of available rooms
            $sql = "SELECT COUNT(*) AS total_room_available FROM rooms WHERE roomstatus = 'Available'";
            $result = $conn->query($sql);

            // Check if there is a result
            if ($result->num_rows > 0) {
                // Fetch the row containing the total number of available rooms
                $row = $result->fetch_assoc();
                $totalRoomsAvailable = $row['total_room_available'];
            } else {
                $totalRoomsAvailable = 0;
            }

            // Close the database connection
            $conn->close();
            ?>

            <h3><?php echo $totalRoomsAvailable; ?></h3>
            <p>Total Available Rooms</p>
        </div>
        <div class="icon">
            <i class="fas fa-bed"></i> <!-- Icon for available rooms -->
        </div>
        <a href="#" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>

    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->

	
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
</body>
</html>
