<?php
function isPageActive($page) {
    // Get the current page filename
    $currentPage = basename($_SERVER['PHP_SELF']);
    
    // Check if the current page matches the specified page
    return $currentPage === $page ? 'active' : '';
}
?>
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Jules Dormitory Monitoring System</title>
<?php require('Style.php'); ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>
<?php require('Navbar.php'); ?>
<?php require('Aside.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
		  <h2><b>DORMITORY HISTORY</b></h2>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
</div>
          <section class="col-lg-12 connectedSortable">

            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
			  <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Room Number</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Balance</th>
					<th>Payment Status</th>
					<th>Payment Date</th>
                  </tr>
                  </thead>
                  <tbody>
                <?php
// Database connection
$conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select all data from the user table
$sql = "SELECT roomnum, name, course, year, balance, paymentstatus, paymentdate FROM user WHERE paymentstatus = 'Partially Paid'";
$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['roomnum']}</td>
                <td>{$row['name']}</td>
                <td>{$row['course']}</td>
                <td>{$row['year']}</td>
                <td>{$row['balance']}</td>
                <td>{$row['paymentstatus']}</td>
                <td>{$row['paymentdate']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No Tenant Data Found</td></tr>";
}

// Close the database connection
$conn->close();
?>
</tbody>
				   </table>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
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
<?php require('Javascript.php'); ?>
</body>
</html>
