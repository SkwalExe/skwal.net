<?php

include $_SERVER['DOCUMENT_ROOT'] . "/php/global.php";

$response = api_response();
api();


if (!isLoggedIn()) {
  $response["error"] = "Not logged in.";
  http_response_code(409);
  echo json_encode($response);
  die();
}

if (requirePost("borders", "color")) {
  $borders = $_POST["borders"];
  $color = $_POST["color"];
  global $defaultSettings;

  if (!in_array($borders, ["show", "hide"])) {
    $response["error"] = "Invalid borders value, expected : show or hide";
    http_response_code(409);
    echo json_encode($response);
    die();
  }


  if (!preg_match("/^#[0-9A-Fa-f]{6}$/i", $color)) {
    $response["error"] = "Invalid hex color value, expected : #RRGGBB";
    http_response_code(409);
    echo json_encode($response);
    die();
  }

  if ($current_user->settings['color'] != $color) {
    $newSettings = $current_user->settings;
    $newSettings['color'] = $color;
    $newSettings = json_encode($newSettings);

    $sql = "UPDATE users SET settings = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$newSettings, $current_user->id]);
  }

  $borders = $borders == "show";

  if ($current_user->settings['borders'] != $borders) {
    $newSettings = $current_user->settings;
    $newSettings["borders"] = $borders;
    $newSettings = json_encode($newSettings);

    $sql = "UPDATE users SET settings = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$newSettings, $current_user->id]);
  }

  $response['message'] = "Settings updated.";
  $response["success"] = true;
  echo json_encode($response);
  http_response_code(200);
} else {
  $response["error"] = "Missing post paramter, required: borders";
  http_response_code(422);
  echo json_encode($response);
}
