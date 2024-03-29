<?php
// Start session at the beginning of the script
session_start();

// Include database connection code here (or use require_once if it's in a separate file)

if(isset($_POST['loginUser'])) {
    // Login logic
    $email = $_POST['loginEmail'];

    // Additional fields
    $name = $_POST['name'];
    $age = $_POST['age'];

    // Include name and age handling logic here if needed

    try {
        $conn = new PDO("mysql:host=localhost;dbname=COSI127b", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT email FROM User WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Login successful: Set session variables
            $_SESSION['user_email'] = $user['email'];
            // Store name and age in session or process them as needed
            $_SESSION['user_name'] = $name;
            $_SESSION['user_age'] = $age;

            echo "<div class='alert alert-success'>Login successful!</div>";
            // Redirect to index.php or another page as required
            header("Location: index.php");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Invalid email.</div>";
        }
    } catch(PDOException $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}

if(isset($_POST['registerUser'])) {
    // Registration logic
    $email = $_POST['registerEmail'];

    // Additional fields
    $name = $_POST['registerName'];
    $age = $_POST['registerAge'];

    // Include name and age handling logic here if needed

    try {
        $conn = new PDO("mysql:host=localhost;dbname=COSI127b", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if user already exists
        $checkStmt = $conn->prepare("SELECT email FROM User WHERE email = ?");
        $checkStmt->execute([$email]);
        if($checkStmt->fetch()) {
            echo "<div class='alert alert-warning'>User already exists.</div>";
        } else {
            $stmt = $conn->prepare("INSERT INTO User (email, name, age) VALUES (?, ?, ?, ?)");
            $stmt->execute([$email, $name, $age]);
            echo "<div class='alert alert-success'>Registration successful!</div>";
            // Log the user in (optional) and set session variables
            $_SESSION['user_email'] = $email;
            // Store name and age in session or process them as needed
            $_SESSION['user_name'] = $name;
            $_SESSION['user_age'] = $age;
            // Redirect to index.php or another page as required
            header("Location: index.php");
            exit;
        }
    } catch(PDOException $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="loginEmail">Email address:</label>
                <input type="email" class="form-control" id="loginEmail" name="loginEmail" required>
            </div>
            <button type="submit" class="btn btn-primary" name="loginUser">Login</button>
        </form>

        <h2>Register</h2>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="registerEmail">Email address:</label>
                <input type="email" class="form-control" id="registerEmail" name="registerEmail" required>
            </div>
            <div class="form-group">
                <label for="registerName">Name:</label>
                <input type="text" class="form-control" id="registerName" name="registerName" required>
            </div>
            <div class="form-group">
                <label for="registerAge">Age:</label>
                <input type="number" class="form-control" id="registerAge" name="registerAge" required>
            </div>
            <button type="submit" class="btn btn-success" name="registerUser">Register</button>
        </form>
        <div class="mt-4">
            <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <?php
    if(isset($_POST['loginUser'])) {
        // Login logic
        $email = $_POST['loginEmail'];

        try {
            $conn = new PDO("mysql:host=localhost;dbname=COSI127b", "root", "");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT email FROM User WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo "<div class='alert alert-success'>Login successful!</div>";
                // Proceed with login logic (e.g., setting session variables)
            } else {
                echo "<div class='alert alert-danger'>Invalid email</div>";
            }
        } catch(PDOException $e) {
            echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }

    if(isset($_POST['registerUser'])) {
        // Registration logic
        $email = $_POST['registerEmail'];

        try {
            $conn = new PDO("mysql:host=localhost;dbname=COSI127b", "root", "");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if user already exists
            $checkStmt = $conn->prepare("SELECT email FROM User WHERE email = ?");
            $checkStmt->execute([$email]);
            if($checkStmt->fetch()) {
                echo "<div class='alert alert-warning'>User already exists.</div>";
            } else {
                $stmt = $conn->prepare("INSERT INTO User (email) VALUES (?, ?)");
                $stmt->execute([$email]);
                echo "<div class='alert alert-success'>Registration successful!</div>";
            }
        } catch(PDOException $e) {
            echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }
    ?>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
