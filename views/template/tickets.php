<?php
require_once __DIR__ . '/../../config/DatabaseManager.php';
require_once __DIR__ . '/../../config/loadEnv.php';
require_once __DIR__ . '/../../models/EvenementsModel.php';
require_once __DIR__ . '/../../controller/EvenementController.php';
require_once __DIR__ . '/../../controller/InscrireController.php';
require_once __DIR__ . '/../../models/InscrireModel.php';
require_once __DIR__ . '/../../config/sessionManager.php';
require_once __DIR__ . '/../partial/usernavbar.php';


loadEnv(__DIR__ . '/../../.env');

$database = new DatabaseManager(
    $_ENV['DB_HOST'],
    (int) $_ENV['DB_PORT'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

if (!isset($_SESSION['id_utilisateur'])) {
    header("Location:login-template.php");
    exit;
};
$inscrireModel = new InscrireModel();
$inscrireController = new InscrireController($inscrireModel);

$inscriptionJson = $inscrireController->afficherInscriptionByUserId($_SESSION['id_utilisateur']);

$inscription = json_decode($inscriptionJson,true);
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Tooplate">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <title>Site Événementiel</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/owl-carousel.css">
    <link rel="stylesheet" href="../../assets/css/tooplate-artxibition.css">
    <link rel="stylesheet" href="../../assets/vendor/css/core.css">
<!--

Tooplate 2125 ArtXibition

https://www.tooplate.com/view/2125-artxibition

-->
    </head>
    
    <body>
    
    <!-- ***** Preloader Start ***** -->
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
    <!-- ***** Preloader End ***** -->
    
    <!-- ***** Header Area Start ***** -->
    <!-- <header class="header-area header-sticky">
        <div class="container">
            <nav class="main-nav">
                <a href="index.html" class="logo">Art<em>Xibition</em></a>
                <ul class="nav">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="shows-events.php">Evenements</a></li>
                    <li><a href="tickets.html">Tickets</a></li> 
                </ul>        
            </nav>
        </div>
    </header> -->
    <!-- ***** Header Area End ***** -->

    <!-- ***** About Us Page ***** -->
    <div class="page-heading-shows-events">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Nos Événements</h2>
                    <span>Voir tous les événements passés et à venir.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="shows-events-tabs">
        <div class="container">
            <div class="row">
                        <div class="col-lg-12">
                            <section class='tabs-content'>
                                <article id='tabs-1'>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="heading"><h2>Événements à venir</h2></div>
                                        </div>
                                        <?php if(!empty($inscritpion['inscription'])) :?>
                                            <?php foreach($inscritpion['inscription'] as $value):?>
                                                <div class="col-lg-12">
                                                    <div class="event-item">
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class='left-content'>
                                                                    <h4><?=htmlspecialchars($value['nom_evenement']) ?></h4>
                                                                    <p><?=htmlspecialchars($value['description_evenement']) ?></p>                                                                                                             
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="thumb">
                                                                <?php 
                                                                    $image = !empty($value['image_evenement']) && file_exists(__DIR__ . "/../../assets/images/" . $value['image_evenement']) 
                                                                        ? $value['image_evenement'] 
                                                                        : 'default.jpg'; 
                                                                    ?>
                                                                    <img src="../../assets/images/<?= htmlspecialchars($image) ?>" alt="Event Image">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="right-content">
                                                                    <ul>
                                                                        <li><i class="fa fa-clock-o"></i>
                                                                            <h6><?=date('M d,Y',strtotime($value['date_evenement']))  ?></h6>
                                                                        </li>
                                                                        <li><i class="fa fa-users"></i>
                                                                            <span>Nombre de place total: <?=htmlspecialchars($value['place_evenement']) ?></span>
                                                                        </li>
                                                                        <li><i class="fa fa-map-marker"></i>
                                                                            <span>Lieu : <?=htmlspecialchars($value['nom_lieu']) ?></span>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else:?>
                                            <p>Pas d'événements à venir</p>
                                        <?php endif;?>
                                    </div>
                                </article>

    <!-- jQuery -->
    <script src="../../assets/js/jquery-2.1.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css">
    <!-- Bootstrap -->
    <script src="../../assets/js/popper.js"></script>
    <script src="../../assets/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Plugins -->
    <script src="../../assets/js/scrollreveal.min.js"></script>
    <script src="../../assets/js/waypoints.min.js"></script>
    <script src="../../assets/js/jquery.counterup.min.js"></script>
    <script src="../../assets/js/imgfix.min.js"></script> 
    <script src="../../assets/js/mixitup.js"></script> 
    <script src="../../assets/js/accordions.js"></script>
    <script src="../../assets/js/owl-carousel.js"></script>

    
    <!-- Global Init -->
    <script src="../../assets/js/custom.js"></script>
  </body>
        
  <style>
    .modal-body ul li span{
        display:block;
        margin-top: 3px;
    }
</style>
</html>
<?php require_once __DIR__ . '/../partial/footer.php';?>
