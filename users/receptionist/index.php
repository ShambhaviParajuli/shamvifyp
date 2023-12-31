<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php require './header.php'; ?>
    <style>
    .dropdown-toggle {
        padding: 5px 10px;
        font-size: 14px;
        width: auto;
        background-color: #3498db;
        color: #fff;
        border: none;
        cursor: pointer;
        border-radius: 4px;
    }

    .doctor-button {
        padding: 8px 12px;
        font-size: 14px;
        text-align: left;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
        background-color: #fff;
        border: 1px solid #ddd;
        cursor: pointer;
        border-radius: 2px;
        color: #333;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 2px;
        overflow: hidden;
        width: auto; /* Set the width to auto */
    }

    .dropdown-content button {
        display: block;
        padding: 8px 12px;
        text-align: left;
        border: none;
        background-color: #fff;
        width: 100%;
        cursor: pointer;
        border-radius: 0; /* Remove border radius */
        color: #333;
    }
</style>



</head>
<?php
$sql = "SELECT * from patient_details";

if (isset($_POST, $_POST['search'])) {
    $s = $_POST['search'];
    $sql = "SELECT * FROM patient_details WHERE fname LIKE '%$s%'";
}

if(isset($_POST, $_POST['doctor_id'], $_POST['patient_id'])){
    $doctorId = $_POST['doctor_id'];
    $patientId = $_POST['patient_id'];
    $stmt = $conn->prepare("INSERT into appointments(user_id, patient_id, status) VALUES(:user_id, :patient_id, :status)");
    $stmt->execute([
        'user_id' => $doctorId,
        'patient_id' => $patientId, 
        'status' => 'pending'
    ]);
    $_SESSION['success'] = "Doctor assigned";
    header("location: index.php");
    exit;
}
?>

<body>
    <div class="container">
        <?php
        displaySidebar($links);
        displayDashboard();
        ?>

        <div class="search-bar">
            <form action="index.php" method="POST">
                <input type="text" name="search" size="30" placeholder="Search Patient">
                <button type="submit" class="search-button">
                    <img src="../img/search.png">
                </button>
            </form>
        </div>

        <div class="body-section">
            <h3>Patient List</h3>
            <div class="list-wrapper">
                <?php
                flashMessages();
                $stmt = $conn->query($sql);
                $stmt->execute();
                $patientDetails = $stmt->fetchAll();
                ?>

                <div class="list <?php echo $stmt->rowCount() > 5? 'list-start-animation' : ''; ?>">
                    <?php
                    if ($stmt->rowCount() > 0) {
                        foreach($patientDetails as $patient){
                    ?>
                    <div class="list-item">
                        <div><img style="border-radius: 50%; height: 5rem;" src="../img/defUser.jpeg"></div>
                        <div style="width: 15rem;"><?=$patient['fname']?></div>
                        <div style="width: 18rem;"><?=$patient['address']??'-'?></div>
                        <div style="width: 10rem;"><?=$patient['phone']??'-'?></div>
                        <div class="dropdown">
                            <button class="dropdown-toggle" onclick="toggleDropdown(this)">Assign Doctor</button>
                            <div class="dropdown-content">
                                <?php
                                $stmt1 = $conn->query("SELECT id, name FROM users WHERE role='DOCTOR'");
                                $stmt1->execute(); 
                                $doctors = $stmt1->fetchAll();
                                if($stmt1->rowCount() > 0){
                                    foreach($doctors as $doc){
                                ?>
                                <form action="index.php" class="dropdown-form" method="POST">
                                    <input type='hidden' name="patient_id" value="<?=$patient['id']?>">
                                    <input type='hidden' name="doctor_id" value="<?=$doc['id']?>">
                                    <button type="submit" class="doctor-button"><?=$doc['name']?></button>
                                </form>
                                <?php
                                    }
                                }
                                else{
                                    echo "No Doctors Available!";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    } else {
                        echo "No data found!";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- div:dashboard-wrapper closing -->
    <div>
    <script>
        function toggleDropdown(button) {
            button.nextElementSibling.classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-toggle')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>

</html>
