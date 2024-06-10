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
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Invoice List</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <button type="button" data-toggle="modal" data-target="#modal-lg" class="btn btn-outline-primary btn-block"><i class="fas fa-edit"></i> Add Invoice</button>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
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
                <h3 class="card-title">Invoice Registration</h3>
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
<?php
// Database connection
$conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all customers with their data for the dropdown
$customers = $conn->query("SELECT id, name, roomnum, roomprice, balance FROM user");

// Close the database connection
$conn->close();
?>
<form method="POST" action="InvoicePayment.php">
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="exampleSelectBorder">Name</label>
                    <select class="custom-select form-control-border" id="name" name="customerName" onchange="updateCustomerDetails(this)">
                        <option value="">Select Customer</option>
                        <?php
                        // Output customer options with embedded data attributes
                        while ($row = $customers->fetch_assoc()) {
                            echo "<option value='" . $row["name"] . "' data-roomnumber='" . $row["roomnum"] . "' data-roomprice='" . $row["roomprice"] . "'>" . $row["name"] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="exampleInputRoomNumber">Room Number</label>
                    <input type="number" class="form-control" id="roomnumber" name="roomNumber" readonly>
                </div>
                <div class="form-group">
                    <label for="exampleInputRoomPrice">Room Price</label>
                    <input type="number" class="form-control" id="roomprice" name="roomPrice" readonly>
                </div>
                <div class="form-group">
                    <label for="exampleInputDown">Payment Amount</label>
                    <input type="number" class="form-control" id="downpayment" name="paymentAmount">
                </div>
                <div class="form-group">
                    <label for="payment-date">Payment Date</label>
                    <input id="payment-date" type="text" class="form-control" disabled>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
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
					<th>Balance</th>
					<th>Amount</th>
					<th>Payment Status</th>
					<th>Payment Date</th>
					<th>Action</th>
                  </tr>
                  </thead>
				  			  					<?php
					// Display the success message if it exists
					if (isset($_SESSION['update'])) {
						echo '
						<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
						<script>
							document.addEventListener("DOMContentLoaded", function() {
								Swal.fire({
									icon: "success",
									title: "UPDATE",
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
// Database connection
$conn = new mysqli("localhost:3306", "root", "", "dormitorysystem");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data from the invoice table
$sql = "SELECT * FROM invoice";
$result = $conn->query($sql);
$date = date('Y-m-d');

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        // Determine if the payment status is fully paid or partially paid
        if ($row['paymentstatus'] === "Fully Paid") {
            // If fully paid, display the payment status without a button
            echo "<tr>
                    <td>{$row['roomnumber']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['balance']}</td>
                    <td>{$row['paymentprice']}</td>
                    <td>{$row['paymentstatus']}</td>
                    <td>{$row['paymentdate']}</td>
                    <td></td>
                </tr>";
        } else {
            // If partially paid, display the payment status with a "Pay Balance" button
            echo "<tr>
                    <td>{$row['roomnumber']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['balance']}</td>
                    <td>{$row['paymentprice']}</td>
                    <td>{$row['paymentstatus']}</td>
                    <td>{$row['paymentdate']}</td>
                    <td><button class='btn btn-outline-primary btn-block' data-toggle='modal' data-target='#updateModal{$row['id']}'>Pay Balance</button></td>
                  </tr>
									  
									  <!-- Modal -->
									  <div class='modal fade' id='updateModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='updateModalLabel{$row['id']}' aria-hidden='true'>
										  <div class='modal-dialog'>
											  <div class='modal-content'>
												  <div class='modal-header'>
													  <h5 class='modal-title' id='updateModalLabel{$row['id']}'>Pay Balance</h5>
													  <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
														  <span aria-hidden='true'>&times;</span>
													  </button>
												  </div>
												  <div class='modal-body'>
													  <form id='payBalanceForm{$row['id']}' action='BalancePayment.php' method='POST'>
													  <input type='hidden' name='id' value='{$row['id']}'>
														  <div class='form-group'>

															  <label for='modalPaymentAmount{$row['id']}'>Payment Amount</label>
															  <input type='number' class='form-control' id='modalPaymentAmount{$row['id']}' name='modalPaymentAmount'>
														  </div>
														  <div class='form-group'>
															  <label for='modalBalance{$row['id']}'>Balance</label>
															  <input type='number' class='form-control' id='modalBalance{$row['id']}' name='modalBalance' value='{$row['balance']}' disabled>
														  </div>
														  <div class='form-group'>
															  <label for='modalPaymentDate{$row['id']}'>Payment Date</label>
															  <input type='text' class='form-control' id='modalPaymentDate{$row['id']}' name='modalPaymentDate' value='$date' disabled>
														  </div>
												  </div>
												  <div class='modal-footer'>
													  <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
													  <button type='submit' class='btn btn-primary' onclick='updateBalance({$row['id']})'>Pay</button>
												  </div>
												  </form>
											  </div>
										  </div>
									  </div>";
        }
    }
} else {
    echo "<tr><td colspan='8'>No data found</td></tr>";
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
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper 
  

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
<!-- SweetAlert2 -->
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
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
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Get the current date
    const today = new Date();
    const formattedDate = (today.getMonth() + 1).toString().padStart(2, '0') + '/' +
                          today.getDate().toString().padStart(2, '0') + '/' +
                          today.getFullYear();

    // Set the values of the input fields
    document.getElementById('payment-date').value = formattedDate;

    // If using inputmask library, initialize it
    if (typeof Inputmask !== 'undefined') {
      Inputmask().mask(document.querySelectorAll("input"));
    }
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
    document.getElementById('payment-date').value = formattedDate;

    // If using inputmask library, initialize it
    if (typeof Inputmask !== 'undefined') {
      Inputmask().mask(document.querySelectorAll("input"));
    }
  });
</script>
<script>
    // JavaScript to populate textboxes based on selected name
    document.getElementById('name').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        document.getElementById('roomnumber').value = selectedOption.getAttribute('data-roomnumber');
        document.getElementById('roomprice').value = selectedOption.getAttribute('data-roomprice');
    });

    // Optional: Enable/disable submit button based on selection
    document.getElementById('submitBtn').addEventListener('click', function() {
        var customerId = document.getElementById('name').value;
        if (customerId === "") {
            alert("Please select a customer");
            return false; // Prevent form submission
        }
        // Proceed with form submission
        // document.forms[0].submit(); // Uncomment this line to submit the form
    });
</script>
<script>
function updateCustomerDetails(selectElement) {
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    var roomNumber = selectedOption.getAttribute('data-roomnumber');
    var roomPrice = selectedOption.getAttribute('data-roomprice');
    
    document.getElementById('roomnumber').value = roomNumber;
    document.getElementById('roomprice').value = roomPrice;
}
</script>
</body>
</html>
