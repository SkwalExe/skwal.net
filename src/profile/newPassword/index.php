<?php
include("{$_SERVER['DOCUMENT_ROOT']}/php/global.php");

$action = "sendmail";

if (requireGet('action', 'id', 'token')) {
  $action = $_GET['action'];
  $id = $_GET['id'];
  $token = $_GET['token'];

  if ($action == 'confirmNewPassword') {
    $user = new User($id);
    if ($user->newPasswordToken == $token) {
      $action = "newPassword";
    } else {
      redirect("/", ["Error", "Invalid token.", "error"]);
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>


  <?php
  metadata([
    "title" => "Reset your password",
    "description" => "Reset your password if you want to change it, or if you forgot it",
  ]);
  css();
  pageCss("form");
  ?>

</head>

<body>

  <?php
  if ($showPageContent) {
    navbarStart();
    navbarButton("Home", "/", "fa fa-home");
    navbarEnd();
  ?>
    <div class="mainContainer">

      <div class="main">
        <div class="small content">
          <h1 class="glowing box center">
            Change/reset your password
          </h1>
          <div class="box glowing">
            <?php
            if ($action == "sendmail") {

            ?>
              <form class="sendMailForm">
                <div class="input">
                  <p class="inputLabel">Your email</p>
                  <input type="email" name="email" required>
                </div>
                <button type="submit">Send email</button>
              </form>
            <?php

            } else {
            ?>

              <form class="newPasswordForm">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="token" value="<?= $token ?>">
                <div class="input">
                  <p class="inputLabel">New password</p>
                  <input autocomplete="new-password" type="password" name="newPassword" required>
                </div>
                <div class="input">
                  <p class="inputLabel">Confirm new password</p>
                  <input autocomplete="new-password" type="password" name="newPasswordConfirmation" required>
                </div>
                <button type="submit">Save</button>
              </form>

            <?php

            }

            ?>
          </div>
        </div>

      </div>

    </div>

  <?php
    loadingScreen();
    footer();
  }
  js();
  pageJs("newPassword");
  ?>

</body>

</html>