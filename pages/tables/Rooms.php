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
  <title>Jules Dormitory Monitoring System</title>

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
    <!-- SweetAlert2 -->
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
<?php require('../../Navbar.php'); ?>
<?php require('../../Aside.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
		  <h2><b>ROOMS LIST</b></h2>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
             <button type="button" data-toggle="modal" data-target="#modal-sm" class="btn btn-outline-primary btn-block toastrDefaultSuccess"><i class="fas fa-edit"></i> Add Room</button>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
		<?php
		// Display the success message if it exists
		if (isset($_SESSION['created'])) {
			echo '
			<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
			<script>
				document.addEventListener("DOMContentLoaded", function() {
					Swal.fire({
						icon: "success",
						title: "CREATED",
						text: "' . $_SESSION['created'] . '",
						toast: true,
						position: "top-end",
						showConfirmButton: false,
						timer: 5000,
						timerProgressBar: true
					});
				});
			</script>';
			unset($_SESSION['created']); // Clear the message after displaying it
		}
		?>
				<div class="modal fade" id="modal-sm">
					<div class="modal-dialog modal-md">
					  <div class="modal-content">
						<div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body">
						<div class="card card-primary">
						  <div class="card-header">
							<h3 class="card-title">Insert Additional Room</h3>
						  </div>

					<form action="InsertRoom.php" method="POST">
						<div class="card-body">
							<div class="form-group">
								<label for="RoomNum">Room Number</label>
								<input type="number" class="form-control" id="RoomNum" name="RoomNum" required>
							</div>
							<div class="form-group">
								<label for="MaxTenant">Tenant Max Count</label>
								<input type="number" class="form-control" id="MaxTenant" name="MaxTenant" required>
							</div>
							<div class="form-group">
								<label for="MonthlyPrice">Monthly Price</label>
								<input type="number" class="form-control" id="MonthlyPrice" name="MonthlyPrice" required>
							</div>
						</div>
						<div class="modal-footer justify-content-between">
							<button type="submit" class="btn btn-outline-success btn-block" id="Submit">Insert Room</button>
						</div>
					</form>

						</div>
						</div>
					  </div>
					  <!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				  </div>


				<!-- Main content -->
				<section class="content">
				  <div class="container-fluid">
					<div class="row">
					  <div class="col-12">
						<div class="card">
						  <!-- /.card-header -->
						  <div class="card-body">
							<table id="example1" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Room No.</th>
										<th>Tenants Maximum Count</th>
										<th>Tenants Occupied Count</th>
										<th>Remaining Slot</th>
										<th>Price Per Tenant</th>
										<th>Action</th>
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

// Fetch data from the database
$sql = "SELECT roomnumber, tenantmax, tenantoccupied, price FROM rooms";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $remainingSlot = $row['tenantmax'] - $row['tenantoccupied'];
        $roomStatus = ($remainingSlot > 0) ? 'Available' : 'Unavailable';

        echo "<tr>
                <td>{$row['roomnumber']}</td>
                <td>{$row['tenantmax']}</td>
                <td>{$row['tenantoccupied']}</td>
                <td>{$remainingSlot}</td>
                <td>{$row['price']}</td>
                <td>{$roomStatus}</td>
                <td>
                    <button class='btn btn-outline-primary btn-block' data-toggle='modal' data-target='#updateModal{$row['roomnumber']}'>Update</button>
                    <button class='btn btn-outline-danger btn-block' onclick='confirmDelete({$row['roomnumber']})'>Remove</button>
                    <button class='btn btn-outline-success btn-block' data-toggle='modal' data-target='#viewModal{$row['roomnumber']}'>View Tenants</button>
                </td>
              </tr>";

        // Update Modal
        echo "<div class='modal fade' id='updateModal{$row['roomnumber']}' tabindex='-1' role='dialog' aria-labelledby='updateModalLabel{$row['roomnumber']}' aria-hidden='true'>
                <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='updateModalLabel{$row['roomnumber']}'>Update Room {$row['roomnumber']}</h5>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                        <form action='UpdateRoom.php' method='POST'>
                            <div class='modal-body'>
                                <input type='hidden' name='roomnumber' value='{$row['roomnumber']}'>
                                <div class='form-group'>
                                    <label for='tenantmax'>Tenant Max</label>
                                    <input type='number' class='form-control' name='tenantmax' value='{$row['tenantmax']}' required>
                                </div>
                                <div class='form-group'>
                                    <label for='price'>Price</label>
                                    <input type='number' class='form-control' name='price' value='{$row['price']}' required>
                                </div>
                            </div>
                            <div class='modal-footer'>
                                <button type='submit' class='btn btn-outline-success btn-block'>Update Room</button>
                            </div>
                        </form>
                    </div>
                </div>
              </div>";
    }
} else {
    echo "<tr><td colspan='7'>No Rooms Found</td></tr>";
}

// Close the database connection
$conn->close();
?>
								</tbody>
							</table>
              </div>
			  
					<?php
					// Display the success message if it exists
					if (isset($_SESSION['update'])) {
						echo '
						<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
						<script>
							document.addEventListener("DOMContentLoaded", function() {
								Swal.fire({
									icon: "success",
									title: "UPDATED",
									text: "' . $_SESSION['update'] . '",
									toast: true,
									position: "top-end",
									showConfirmButton: false,
									timer: 5000,
									timerProgressBar: true
								});
							});
						</script>';
						unset($_SESSION['update']); // Clear the message after displaying it
					}
					?>

					<?php
					// Display the success message if it exists
					if (isset($_SESSION['remove'])) {
						echo '
						<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
						<script>
							document.addEventListener("DOMContentLoaded", function() {
								Swal.fire({
									icon: "success",
									title: "REMOVE",
									text: "' . $_SESSION['remove'] . '",
									toast: true,
									position: "top-end",
									showConfirmButton: false,
									timer: 5000,
									timerProgressBar: true
								});
							});
						</script>';
						unset($_SESSION['remove']); // Clear the message after displaying it
					}
					?>
			      <?php
					// Database connection (moved to ensure connection is established before modal creation)
					$conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

					// Check connection
					if ($conn->connect_error) {
						die("Connection failed: " . $conn->connect_error);
					}

					// Fetch data from the database and create modals for each room
					$sql = "SELECT roomnumber FROM rooms";
					$result = $conn->query($sql);

					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							// Viewing Modal
							echo "<div class='modal fade' id='viewModal{$row['roomnumber']}' tabindex='-1' role='dialog' aria-labelledby='viewModalLabel{$row['roomnumber']}' aria-hidden='true'>
								<div class='modal-dialog modal-lg' role='document'>
									<div class='modal-content'>
										<div class='modal-header'>
											<h5 class='modal-title' id='viewModalLabel{$row['roomnumber']}'>Tenants in Room {$row['roomnumber']}</h5>
											<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
												<span aria-hidden='true'>&times;</span>
											</button>
										</div>
										<div class='modal-body'>
											<table class='table table-bordered'>
												<thead>
													<tr>
														<th>Name</th>
														<th>Age</th>
														<th>Course</th>
														<th>Year</th>
														<th>Section</th>
													</tr>
												</thead>
												<tbody>";

							// Fetch tenant details for the room
							$tenantSql = "SELECT name, course, year, age, section FROM user WHERE roomnum = {$row['roomnumber']}";
							$tenantResult = $conn->query($tenantSql);

							if ($tenantResult->num_rows > 0) {
								while ($tenantRow = $tenantResult->fetch_assoc()) {
									echo "<tr>
											<td>{$tenantRow['name']}</td>
											<td>{$tenantRow['age']}</td>
											<td>{$tenantRow['course']}</td>
											<td>{$tenantRow['year']}</td>
											<td>{$tenantRow['section']}</td>
										</tr>";
								}
							} else {
								echo "<tr><td colspan='7'>No Tenants Found.</td></tr>";
							}

							echo "</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>";
						}
					}
					?>

              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
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
<!-- SweetAlert2 -->
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
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
<script>
        function confirmDelete(roomNumber) {
            Swal.fire({
                title: 'Are You Sure?',
                text: 'You Won\'t Be Able To Revert This!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to RemoveRoom.php with the room number
                    window.location.href = 'RemoveRoom.php?roomnumber=' + roomNumber;
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    Swal.fire(
                        'Cancelled'
                    )
                }
            })
        }
    </script>
</body>
</html>
