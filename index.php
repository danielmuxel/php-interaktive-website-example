<?php
// Hardcoded list of user accounts
$users = [
  [
    'username' => 'peter.muster',
    'password' => '123'
  ],
  // Add more users as needed
];

date_default_timezone_set('Europe/Zurich'); // Set the default timezone to use in the date formatter so that the date is displayed in the correct timezone


// Get the last login date from the cookie and format it
if (isset($_COOKIE['last_login_date'])) {
  $lastLoginDate = new DateTime($_COOKIE['last_login_date']);
  $formatter = new IntlDateFormatter(
    'de_DE',
    IntlDateFormatter::FULL,
    IntlDateFormatter::SHORT,
    date_default_timezone_get(),
    IntlDateFormatter::GREGORIAN,
    'EEEE, dd. MMMM yyyy \'um\' HH:mm:ss'
  );
  $formattedLastLoginDate = $formatter->format($lastLoginDate);
} else {
  $formattedLastLoginDate = null;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Retrieve the submitted form data
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  $saveLoginDate = $_POST['save_login_date'] ?? '';

  // Check if the username and password are valid
  $userFound = false;
  foreach ($users as $user) {
    if ($user['username'] === $username && $user['password'] === $password) {
      $userFound = true;
      break; // Stop the loop if the user is found
    }
  }

  if ($userFound) {
    // Save the login in a cookie
    setcookie('login', $username, time() + 86400 * 30); // Cookie expires in 30 days

    // Save the last login date in a cookie if the checkbox is checked
    if ($saveLoginDate) {
      setcookie('last_login_date', date('Y-m-d H:i:s'), time() + 86400 * 30); // Cookie expires in 30 days
    }

    // Redirect to the success page
    header('Location: form.php');
    exit;
  } else {
    $errorMessage = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login Form</title>
</head>

<body>
  <?php if (isset($errorMessage)) : ?>
    <p style="color: red;"><?php echo $errorMessage; ?></p>
  <?php endif; ?>
  <form method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    <br>
    <label for="save_login_date">Save login date:</label>
    <input type="checkbox" name="save_login_date" checked id="save_login_date">
    <br>
    <button type="submit">Login</button>
  </form>
  <?php if ($formattedLastLoginDate) : ?>
    <p>Last logged in: <?php echo $formattedLastLoginDate; ?></p>
  <?php endif; ?>
</body>

</html>