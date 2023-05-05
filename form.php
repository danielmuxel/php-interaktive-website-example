<?php
// Check if the user is logged in
if (!isset($_COOKIE['login'])) {
  header('Location: index.php');
  exit;
}

// Get the username from the cookie
$username = $_COOKIE['login'];

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
  $data = [
    'username' => $_POST['username'] ?? '',
    'firstname' => $_POST['firstname'] ?? '',
    'lastname' => $_POST['lastname'] ?? '',
    'email' => $_POST['email'] ?? '',
    'newsletter' => isset($_POST['newsletter']),
    'accompany' => [
      'firstname' => $_POST['accompany_firstname'] ?? '',
      'lastname' => $_POST['accompany_lastname'] ?? ''
    ],
    'date' => $_POST['date'] ?? ''
  ];

  // Save the form data as a JSON file on the server
  $filename = 'form_data_' . date('Y-m-d_H-i-s') . '.json';
  // $filename = 'form_data.json'; // Use this filename to overwrite the same file
  file_put_contents($filename, json_encode($data));

  // Redirect to the success page
  header('Location: success.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Form</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <p>Login confirmation: Welcome, <?php echo htmlspecialchars($username); ?>!</p>
  <?php if ($formattedLastLoginDate) : ?>
    <p>Last logged in: <?php echo $formattedLastLoginDate; ?></p>
  <?php endif; ?>

  <h1>Form</h1>

  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

  <form method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" value="<?= htmlspecialchars($username); ?>" disabled>
    <br>
    <label for="firstname">First name:</label>
    <input type="text" name="firstname" id="firstname" required>
    <br>
    <label for="lastname">Last name:</label>
    <input type="text" name="lastname" id="lastname" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
    <br>
    <label for="newsletter">Register for the newsletter:</label>
    <input type="checkbox" name="newsletter" id="newsletter">
    <br>
    <fieldset>
      <legend>Accompany</legend>
      <label for="accompany_firstname">First name:</label>
      <input type="text" name="accompany_firstname" id="accompany_firstname">
      <br>
      <label for="accompany_lastname">Last name:</label>
      <input type="text" name="accompany_lastname" id="accompany_lastname">
    </fieldset>
    <br>
    <fieldset>
      <legend>Date</legend>
      <input type="radio" name="date" id="date1" value="Mittwoch, 24. Mai 2023, 20:00-22:00 Uhr" required>
      <label for="date1">Mittwoch, 24. Mai 2023, 20:00-22:00 Uhr</label>
      <br>
      <input type="radio" name="date" id="date2" value="Donnerstag, 25. Mai 2023, 20:00-22:00 Uhr">
      <label for="date2">Donnerstag, 25. Mai 2023, 20:00-22:00 Uhr
      </label>
      <br>
      <input type="radio" name="date" id="date3" value="Freitag, 26. Mai 2023, 20:00-22:00 Uhr">
      <label for="date3">Freitag, 26. Mai 2023, 20:00-22:00 Uhr</label>
    </fieldset>
    <br>
    <button type="submit">Send</button>
    <button type="reset">Reset</button>
  </form>
</body>

</html>