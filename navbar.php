<!DOCTYPE html>
<html>
<body>
<header>

<div class = "container">
  <br>
  <div class = "row p">
    <div class = "col-md-9 mx-auto ">
      <?php if (isset($_SESSION['49_CostCut'])):?>
        <ul class="nav nav-pills nav-fill ">
          <li class="nav-item"><a href="count.php" class="nav-link">COUNT</a></li>
          <li class="nav-item"><a href="edit.php" class="nav-link">EDIT</a></li>
          <li class="nav-item"><a href="admin/signout.php" class="nav-link">LOGOUT</a></li>
        </ul>
      <?php else :?>
        <ul class="nav nav-pills nav-fill">
          <li class="nav-item"><a href="index.php" class="nav-link">TOP</a></li>
          <li class="nav-item"><a href="about.html" class="nav-link">ABOUT</a></li>
          <li class="nav-item"><a href="admin/login.php" class="nav-link">LOGIN</a></li>
        </ul>
      <?php endif;?>
    </div>
  </div>
</div>
<br>
</header>
</body>
</html>
