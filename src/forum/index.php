<?php
include("{$_SERVER['DOCUMENT_ROOT']}/php/global.php");

$sql = "SELECT id FROM users";
$stmt = $db->prepare($sql);
$stmt->execute();
$userCount = $stmt->rowCount();

$sql = "SELECT id FROM posts";
$stmt = $db->prepare($sql);
$stmt->execute();
$postCount = $stmt->rowCount();

$sql = "SELECT id FROM users ORDER BY createdAt DESC LIMIT 1";
$stmt = $db->prepare($sql);
$stmt->execute();
$lastUser = $stmt->fetch();
$lastUser = new User($lastUser['id']);

$sql = "SELECT id FROM posts ORDER BY createdAt DESC LIMIT 1";
$stmt = $db->prepare($sql);
$stmt->execute();
$lastPost = $stmt->fetch();
$lastPost = new Post($lastPost['id']);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  metadata([
    "title" => '💬 Skwal.net forum',
    "description" => "Skwal.net forum is a safe, welcoming and caring place to discover cool stuff, share your knowledge and get help from other users"
  ]);
  css();
  pageCss('form', "post", "searchBar", "avatar", "prism");
  ?>
</head>

<body>
  <?php
  if ($showPageContent) {
    navbarStart();
    if (isLoggedIn()) {
      navbarButton("Profile", "/profile", "fa-solid fa-user");
      navbarButton("New Post", "/profile/newPost", "fa-solid fa-plus");
    } else
      navbarButton("Login", "/login", "fa fa-sign-in");
    navbarButton("Home", "/", "fa fa-home");
    navbarEnd();
  ?>
    <div class="mainContainer">
      <div class="main">
        <div class="content">
          <form method="GET" action="/search" class="box glowing search-bar">
            <input placeholder="How to split a string with javascript" required name="q" type="text">
            <button><i class="fa fa-search"></i></button>
          </form>
          <h1 class="center box glowing">Recent posts</h1>

          <?php
          foreach (recentPosts(10) as $post) {
            echo "<div toultip='Click to open post' style='max-height: 300px; overflow-y: auto' href='/post/?id={$post->id}'>";
            echo $post->HTML();
            echo "</div>";
          }
          ?>
        </div>
        <hr class="onlyShowWhenMobileWidth">
        <div class="sidebar">
          <h1 class="box glowing center">
            Stats
          </h1>
          <div class="links box glowing">
            <p>Users : <span class="color"><?= $userCount ?></span></p>
            <p>Posts : <span class="color"><?= $postCount ?></span></p>
            <p>Newest user : <a href="<?= $lastUser->profileHTML ?>"><?= htmlentities($lastUser->username) ?></a></p>
            <p>Newest post : <a href="/post?id=<?= $lastPost->id ?>"><?= htmlentities($lastPost->title) ?></a></p>
          </div>
          <hr>
          <h1 class="box glowing center">
            Pages
          </h1>
          <?php
          pages();
          ?>
          <hr>
          <h1 class="box glowing center">
            Projects
          </h1>
          <?php
          projects();
          ?>
        </div>
      </div>
    </div>
  <?php
    loadingScreen();
    footer();
  }
  js();
  pageJs("post", "prism");
  ?>
</body>

</html>