
<header>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
      <?php if(isset($_SESSION['user'])) : ?>
        <li class="nav-item">
          <a class="nav-link" href='../index.php'>Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href='../asset/read.php'>Read</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href='../asset/create.php'>Create</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href='../asset/profile.php'>Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href='../asset/logout.php'>Logout</a>
        </li>
        <?php else : ?>
        <li class="nav-item">
          <a class="nav-link" href='../asset/login.php'>Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href='../asset/register.php'>Sign Up</a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

</header>