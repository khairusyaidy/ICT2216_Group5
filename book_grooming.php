<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connect.php';

// Retrieve CustomerID from session
if (!isset($_SESSION['id'])) {
    // Handle case where customer ID is not set in session
    die("Customer ID not found in session.");
}
$customerID = $_SESSION['id'];
$result = $conn->query("select * from pet where CustomerID = $customerID");

$pet_name = [];
while ($row = $result->fetch_assoc()) {
    $pet_name[] = $row;
}

// Fetch unavailable date ranges
$unavailable_dates = [];
$availability_query = "SELECT StartDate, EndDate FROM availability WHERE Deleted_At IS NULL";
$availability_result = $conn->query($availability_query);
while ($row = $availability_result->fetch_assoc()) {
    $unavailable_dates[] = $row;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['pet'], $_POST['grooming_date'], $_POST['selected_service_name'], $_POST['selected_service_price'])) {
        // Re-establish the connection
        require_once 'db_connect.php';

        // Get the form data
        $pet_id = $_POST['pet'];
        $grooming_date = $_POST['grooming_date'];
        $selected_service_name = $_POST['selected_service_name'];
        $selected_service_price = $_POST['selected_service_price'];

        $stmt = $conn->prepare("SELECT ID FROM service WHERE ServiceName = ?");
        $stmt->bind_param("s", $selected_service_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $service = $result->fetch_assoc();
        $service_id = $service['ID'];

        // Fetch PetWeight
        $pet_query = "SELECT Weight FROM pet WHERE ID = '$pet_id'";
        $pet_result = $conn->query($pet_query);
        $pet = $pet_result->fetch_assoc();
        $pet_weight = $pet['Weight'];

        // Insert data into the booking table
        $sql = "INSERT INTO booking (DropOffDate, PickUpDate, Food, Remarks, TotalPrice, Paid, ServiceID, CustomerID, PetID, PetWeight, ReviewID) 
            VALUES ('$grooming_date', '$grooming_date', 0, 'Nil', '$selected_service_price', 0, '$service_id', '$customerID', '$pet_id', '$pet_weight', NULL)";

        if ($conn->query($sql) === TRUE) {
            // Retrieve the auto-generated ID of the inserted record
            $booking_id = $conn->insert_id; // This will give you the ID of the inserted booking
            // Retrieve TotalPrice for the booking ID
            $get_price_sql = "SELECT TotalPrice FROM booking WHERE ID = '$booking_id'";
            $result = $conn->query($get_price_sql);
            $row = $result->fetch_assoc();
            $total_price = $row['TotalPrice'];

            // Store booking ID and TotalPrice in session (for example)
            $_SESSION['booking_id'] = $booking_id;
            $_SESSION['total_price'] = $total_price;

            // Redirect to the payment page or display a success message
            header('Location: payment_method.php');
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        //Close the connection
        $conn->close();
    }
}

////Close the connection
//$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <?php
        include "head.inc.php";
        ?>
        <link rel="stylesheet" href="css/book_grooming.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    </head>

    <body>
        <!-- Topbar Start -->
        <?php
        include "topbar.inc.php";
        ?>
        <!-- Topbar End -->


        <!-- Navbar Start -->
        <?php
        include "nav.inc.php";
        ?>
        <!-- Navbar End -->


        <div id="booking_successful_container">
            <br>
            <h2>Please complete below form to book</h2>

            <form method="post" action="" onsubmit="return validateDates()">

                <p><b>Select a pet:</b></p>

                <?php foreach ($pet_name as $pname): ?>
                    <div>
                        <input type="radio" id="pet_<?php echo htmlspecialchars($pname['ID']); ?>" name="pet" value="<?php echo htmlspecialchars($pname['ID']); ?>" data-weight="<?php echo htmlspecialchars($pname['Weight']); ?>" required>
                        <label for="pet_<?php echo htmlspecialchars($pname['ID']); ?>"><?php echo htmlspecialchars($pname['Name']); ?></label>
                    </div>
                <?php endforeach; ?>

                <br>

                <div id="services_container"></div>

                <br>

                <label for="grooming_date"><b>Date:</b></label><br>
                <input type="date" id="grooming_date" name="grooming_date" class="flatpickr">

                <!-- Hidden input fields to store selected service -->
                <input type="hidden" id="selected_service_name" name="selected_service_name">
                <input type="hidden" id="selected_service_price" name="selected_service_price">

                <br><br>
                <div class="button-container">
                    <input type="submit" id="grooming_btn" name="book" value="Book" class="btn btn-primary btn-block p-3">
                </div>
            </form>
        </div>


        <!-- Footer Start -->
        <?php
        include "footer.inc.php";
        ?>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/tempusdominus/js/moment.min.js"></script>
        <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
        <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

        <!-- Contact Javascript File -->
        <script src="mail/jqBootstrapValidation.min.js"></script>
        <script src="mail/contact.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const unavailableDates = <?php echo json_encode($unavailable_dates); ?>;

                function getDisabledDates(unavailableDates) {
                    let disabledDates = [];
                    unavailableDates.forEach(range => {
                        let start = new Date(range.StartDate);
                        let end = new Date(range.EndDate);
                        while (start <= end) {
                            disabledDates.push(start.toISOString().split('T')[0]);
                            start.setDate(start.getDate() + 1);
                        }
                    });
                    return disabledDates;
                }

                const disabledDates = getDisabledDates(unavailableDates);

                flatpickr(".flatpickr", {
                    disable: disabledDates,
                    dateFormat: "Y-m-d",
                    minDate: "today"
                });
            });

            function validateDates() {

                // Check if the date is not empty
                if (!document.getElementById('grooming_date').value) {
                    alert('Please ensure the date is selected.');
                    return false;
                }

                return true;
            }

            $(document).ready(function () {
                $('input[name="pet"]').on('change', function () {
                    var petID = $(this).val();
                    $.ajax({
                        url: 'fetch_services.php',
                        type: 'POST',
                        data: {petID: petID},
                        success: function (response) {
                            $('#services_container').html(response);
                        }
                    });
                });

                // Handle service selection
                $(document).on('change', 'input[name="service"]', function () {
                    var serviceName = $(this).data('name');
                    var servicePrice = $(this).data('price');

                    // Set the selected service values to hidden input fields or store in JavaScript variables
                    $('#selected_service_name').val(serviceName);
                    $('#selected_service_price').val(servicePrice);
                });
            });

        </script>
    </body>

</html>
