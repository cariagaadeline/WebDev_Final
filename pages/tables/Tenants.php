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
    <!-- daterange picker -->
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
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
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Tenants List</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <button type="button" data-toggle="modal" data-target="#modal-lg" class="btn btn-outline-primary btn-block"><i class="fas fa-edit"></i> Add Tenants</button>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
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
	
	<div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Tenants Registration</h3>
              </div>
              <!-- /.card-header -->
              <form action="InsertTenants.php" method="POST">
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <?php
                    // Database connection
                    $conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // SQL query to fetch available room numbers and their prices
                    $sql = "SELECT roomnumber, price FROM rooms WHERE roomstatus = 'available'";
                    $result = $conn->query($sql);

                    // Check if there are any rows returned
                    if ($result->num_rows > 0) {
                        echo '<label for="roomNumber">Room Number</label>';
                        echo '<select class="custom-select form-control-border" id="roomNumber" name="roomNumber">';
                        
                        // Add an option for "Select Room"
                        echo '<option value="" disabled selected>Select Room</option>';
                        
                        // Output options for select dropdown
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['roomnumber'] . '" data-price="' . $row['price'] . '">' . $row['roomnumber'] . '</option>';
                        }
                        
                        echo '</select>';
                    } else {
                        echo 'No available rooms found';
                    }

                    // Close the database connection
                    $conn->close();
                    ?>
                </div>
                <div class="form-group">
                    <label for="roomPrice">Room Price</label>
                    <input type="number" class="form-control" id="roomPrice" name="roomPrice" value="" readonly>
                </div>
                <div class="form-group">
                    <label for="downPayment">Down Payment</label>
                    <input type="number" class="form-control" id="downPayment" name="downPayment">
                </div>
                <div class="form-group">
                    <label for="balance">Balance</label>
                    <input type="number" class="form-control" id="balance" name="balance" readonly>
                </div>
                <div class="form-group">
                    <label for="payment-date">Payment Date</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input id="payment-date" type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label for="start-date">Start Date</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input id="start-date" type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask disabled>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" class="form-control" id="age" name="age">
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select class="custom-select form-control-border" id="gender" name="gender">
                        <option value="" disabled selected>Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" rows="3" id="address" name="address"></textarea>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="course">Course</label>
                            <input type="text" class="form-control" id="course" name="course">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="year">Year</label>
                            <input type="number" class="form-control" id="year" name="year">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="section">Section</label>
                            <input type="text" class="form-control" id="section" name="section">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" class="btn btn-outline-success btn-block">Insert Tenant</button>
    </div>
</form>
            </div>
            <!-- /.card -->
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
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
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Year</th>
                                    <th>Course</th>
                                    <th>Balance</th>
                                    <th>Paid Amount</th>
                                    <th>Payment Status</th>
                                    <th>Room Price</th>
                                    <th>Payment Date</th>
                                    <th>Starting Date</th>
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

// SQL query to fetch all data from the user table
$sql = "SELECT * FROM user";
$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        echo "<tr>
                <td>{$row['roomnum']}</td>
                <td>{$row['name']}</td>
                <td>{$row['gender']}</td>
                <td>{$row['year']}</td>
                <td>{$row['course']}</td>
                <td>{$row['balance']}</td>
                <td>{$row['paymentprice']}</td>
                <td>{$row['paymentstatus']}</td>
                <td>{$row['roomprice']}</td>
                <td>{$row['paymentdate']}</td>
                <td>{$row['startingdate']}</td>
                <td>
                    <button class='btn btn-outline-primary btn-block' data-toggle='modal' data-target='#updateModal{$id}'>Update</button>
                    <button class='btn btn-outline-danger btn-block' onclick='confirmDelete(\"{$id}\")'>Remove</button>
                    <button class='btn btn-outline-success btn-block' data-toggle='modal' data-target='#viewModal{$id}'>View</button>
                </td>
              </tr>";
        // Update Modal
        echo "<div class='modal fade' id='updateModal{$id}' tabindex='-1' role='dialog' aria-labelledby='updateModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-lg' role='document'>
        <div class='modal-content'>
            <div class='modal-body'>
                <!-- Update form content -->
                <form action='UpdateTenant.php' method='POST'>
                    <!-- Include form fields here for updating tenant information -->
                    <input type='hidden' name='id' value='{$id}'>
                    <!-- Add other input fields as needed -->
                    <div class='card card-primary'>
                        <div class='card-header'>
                            <h3 class='card-title'>Tenants Info Update</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class='card-body'>
                            <div class='row'>
                                <div class='col-6'>
									<div class='form-group'>
                                        <label for='name'>Name</label>
                                        <input type='text' class='form-control' name='name' value='{$row['name']}'>
                                    </div>
								    <div class='form-group'>
                                        <label for='gender'>Gender</label>
                                        <input type='text' class='form-control' name='gender' value='{$row['gender']}'>
                                    </div>
									<div class='form-group'>
                                        <label for='age'>Age</label>
                                        <input type='text' class='form-control' name='age' value='{$row['age']}'>
                                    </div>
									<div class='form-group'>
                                        <label for='address'>Address</label>
                                        <textarea class='form-control' name='address'>{$row['address']}</textarea>
                                    </div>
                                </div>
                                <div class='col-6'>
								    <div class='form-group'>
                                        <label for='course'>Course</label>
                                        <input type='text' class='form-control' name='course' value='{$row['course']}'>
                                    </div>
									<div class='form-group'>
                                        <label for='year'>Year</label>
                                        <input type='text' class='form-control' name='year' value='{$row['year']}'>
                                    </div>
									<div class='form-group'>
                                        <label for='section'>Section</label>
                                        <input type='text' class='form-control' name='section' value='{$row['section']}'>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class='card-footer'>
                            <button type='submit' class='btn btn-outline-success btn-block'>Update Info</button>
                        </div>
                    </div>
                    <!-- /.card -->
                </form>
            </div>
        </div>
    </div>
</div>
";
        // Remove Confirmation Modal
        echo "<div class='modal fade' id='removeModal{$id}' tabindex='-1' role='dialog' aria-labelledby='removeModalLabel' aria-hidden='true'>
                <div class='modal-dialog' role='document'>
                  <div class='modal-content'>
                    <div class='modal-header'>
                      <h5 class='modal-title' id='removeModalLabel'>Confirm Delete</h5>
                      <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                      </button>
                    </div>
                    <div class='modal-body'>
                      Are you sure you want to delete this tenant?
                    </div>
                    <div class='modal-footer'>
                      <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                      <form action='deleteTenant.php' method='POST'>
                        <input type='hidden' name='id' value='{$id}'>
                        <button type='submit' class='btn btn-danger'>Delete</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>";
    }
} else {
    echo "<tr><td colspan='14'>No Tenant Data Found</td></tr>";
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
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<?php
// Database connection
$conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch distinct user names from the database
$sql = "SELECT DISTINCT name, id FROM invoice";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // User name and ID
        $userName = $row['name'];
        $userId = $row['id'];
        
        // Fetch transaction history for the current user
        $sqlHistory = "SELECT * FROM invoice WHERE name = '$userName'";
        $resultHistory = $conn->query($sqlHistory);
        
        // Viewing Modal
        echo "<div class='modal fade' id='viewModal{$userId}' tabindex='-1' role='dialog' aria-labelledby='viewModalLabel{$userId}' aria-hidden='true'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    
                    <div class='modal-body'>
                        <h6>Transaction History for $userName</h6>
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>Balance</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                    <th>Payment Date</th>
                                </tr>
                            </thead>
                            <tbody>";
        
        // Display transaction history
        if ($resultHistory->num_rows > 0) {
            while ($rowHistory = $resultHistory->fetch_assoc()) {
                echo "<tr>
                        <td>{$rowHistory['balance']}</td>
                        <td>{$rowHistory['paymentprice']}</td>
                        <td>{$rowHistory['paymentstatus']}</td>
                        <td>{$rowHistory['paymentdate']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No Transaction History Found for $userName</td></tr>";
        }
        
        echo "          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>";
    }
} else {
    echo "No Users Found.";
}

// Close the database connection
$conn->close();
?>

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
  document.addEventListener("DOMContentLoaded", function() {
    // Get the current date
    const today = new Date();
    const formattedDate = (today.getMonth() + 1).toString().padStart(2, '0') + '/' +
                          today.getDate().toString().padStart(2, '0') + '/' +
                          today.getFullYear();

    // Set the values of the input fields
    document.getElementById('start-date').value = formattedDate;
    document.getElementById('payment-date').value = formattedDate;

    // If using inputmask library, initialize it
    if (typeof Inputmask !== 'undefined') {
      Inputmask().mask(document.querySelectorAll("input"));
    }
  });
</script>
<script>
    // Add event listener to the select dropdown
    document.getElementById('roomNumber').addEventListener('change', function() {
        // Get the selected option
        var selectedOption = this.options[this.selectedIndex];
        // Get the price from the data-price attribute of the selected option
        var price = selectedOption.getAttribute('data-price');
        // Update the value of the room price input field
        document.getElementById('roomPrice').value = price;
    });
</script>
<script>
    // Function to calculate the balance
    function calculateBalance() {
        // Get the value of the down payment
        var downPayment = parseInt(document.getElementById('downPayment').value);
        // Get the room price from the previously selected option
        var roomPrice = parseInt(document.getElementById('roomPrice').value);
        
        var remainingBalance = (downPayment === roomPrice) ? 0 : Math.max(roomPrice - downPayment, 0);

        // Set the value of the balance input field
        document.getElementById('balance').value = remainingBalance;
    }

    // Add event listener to the down payment input field
    document.getElementById('downPayment').addEventListener('input', calculateBalance);
</script>

<script>
    // Add event listener to the room selection dropdown
    document.getElementById('roomNumber').addEventListener('change', function() {
        // Clear the down payment, covered months, and balance fields
        document.getElementById('downPayment').value = '';
        document.getElementById('balance').value = '';
    });
</script>
<script>
        function confirmDelete(id) {
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
                    window.location.href = 'RemoveTenants.php?id=' + id;
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
