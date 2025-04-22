<!-- Page CSS -->
<link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
     <link rel="stylesheet" href="../../../assets/css/tooplate-artxibition.css">


<div id="js-preloader" class="js-preloader">
      <div class="preloader-inner">
        <span class="dot"></span>
        <div class="dots">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>
    
<!-- Preloader end-->
<header class="header-area header-sticky">
        <div class="container">
            <nav class="main-nav">
                <a href="index.html" class="logo">Site<em> Événement</em></a>
                <ul class="nav">
                    <li><a href="/PHP2/views/template/shows-events.php">Événements</a></li>                    
                    <?php require_once __DIR__ . '/../../config/sessionManager.php';
                    if(isset($_SESSION['role_utilisateur']) && $_SESSION['role_utilisateur'] === 'user'): ?> 
                    <li><a href="/PHP2/views/template/tickets.php">Mes tickets</a></li>
                    <li><a href="" class="active">Paramètre</a><li>
                    <li><a href="" id="logout-link">Deconnexion</a></li>
                    <?php endif; ?>
                    <?php if(!isset($_SESSION['role_utilisateur'])): ?> 
                    <li><a href="/PHP2/views/template/login-template.php">Login</a></li>
                    <?php endif; ?>
                   
                </ul>        
            </nav>
        </div>
    </header>

    <!-- Bootstrap -->
<script src="../../../assets/js/jquery-2.1.0.min.js"></script>
<script src="../../../assets/js/bootstrap.min.js"></script>
<script src="../../../assets/js/custom.js"></script>
<script src="../../../assets/js/preloader-loop-halt.js"></script>

<script>
  document.getElementById('logout-link').addEventListener('click', function(e) {
    e.preventDefault();

    fetch('/PHP2/api/logout', {
        method: 'POST',
        credentials: 'include'
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            window.location.href = "/PHP2/views/template/login-template.php"; // rediriger vers login
        } else {
            alert("Erreur de déconnexion.");
        }
    })
    .catch(error => console.error("Erreur:", error));
});


</script>