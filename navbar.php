 <header>
      <nav class="navbar">
        <a class="navbar-brand"></a>
        <div class="dropdown">
          <button type="button" id="dropdownMenuButton" class="btn btn-secondary dropdown-toggle m-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            メニュー
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="z-index:9999">
            <?php if (isset($_SESSION['49_CostCut'])):?>
            <a class="dropdown-item" href="count.php">管理者画面TOP</a>
            <a class="dropdown-item" href="edit.php">編集</a>
            <a class="dropdown-item" href="signout.php">ログアウト</a>
            <?php else :?>
            <a class="dropdown-item" href="index.php">TOP </a>
            <a class="dropdown-item" href="プロフィール/profile.html">About</a>
            <a class="dropdown-item" href="admin/login.php">管理者ログイン</a>
            <?php endif;?>
          </div>
        </div>
      </nav>
    </header>
   <!--  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="z-index:9999">
            <a class="dropdown-item" href="index.php">TOP </a>
            <a class="dropdown-item" href="プロフィール/profile.html">About</a>
            <?php if (!isset($_SESSION['49_CostCut'])):?>
            <a class="dropdown-item" href="admin/login.php">管理者ログイン</a>
            <?php else :?>
             <a class="dropdown-item" href="edit.php">編集</a>
            <a class="dropdown-item" href="signout.php">ログアウト</a>
            <?php endif;?>
          </div> -->