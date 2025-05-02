<?php
require_once __DIR__ . '/../../config/DatabaseManager.php';
require_once __DIR__ . '/../../config/loadEnv.php';
require_once __DIR__ . '/../../models/EvenementsModel.php';
require_once __DIR__ . '/../../controller/EvenementController.php';
require_once __DIR__ . '/../../config/sessionManager.php';


loadEnv(__DIR__ . '/../../.env');

$database = new DatabaseManager(
    $_ENV['DB_HOST'],
    (int) $_ENV['DB_PORT'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

$event_model=new EvenementsModel();
$event_controller=new EvenementController($event_model);

$upcomingEventsJson=$event_controller->getUpcomingEvents();
$pastEventsJson=$event_controller->getPassEvents();

$upcomingEvents=json_decode($upcomingEventsJson,true);
$pastEvents=json_decode($pastEventsJson,true);

?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Tooplate">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <title>Site Événement</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/owl-carousel.css">
    <link rel="stylesheet" href="../../assets/css/tooplate-artxibition.css">
<!--

Tooplate 2125 ArtXibition

https://www.tooplate.com/view/2125-artxibition

-->
    </head>
    
    <body>
    <?php require_once __DIR__ . '/../partial/usernavbar.php';?>

    <!-- ***** About Us Page ***** -->
    <div class="page-heading-shows-events">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Nos Événements</h2>
                    <span>Consultez nos événements passés et à venir.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="shows-events-tabs">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row" id="tabs">
                        <div class="col-lg-12">
                            <div class="heading-tabs">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <ul>
                                          <li><a href='#tabs-1'>À venir</a></li>
                                          <li><a href='#tabs-2'>Passé</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <section class='tabs-content'>
                                <article id='tabs-1'>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="heading"><h2>Événéments à venir</h2></div>
                                        </div>
                                        <?php if(!empty($upcomingEvents['events'])) :?>
                                            <?php foreach($upcomingEvents['events'] as $event):?>
                                                <div class="col-lg-12">
                                                    <div class="event-item">
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class='left-content'>
                                                                    <h4><?=htmlspecialchars($event['nom_evenement']) ?></h4>
                                                                    <p><?=htmlspecialchars($event['description_evenement']) ?></p>
                                                                    <div class="main-dark-button">
                                                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal" 
                                                                            data-id="<?= $event['id_evenement'] ?>"
                                                                            data-nom="<?= htmlspecialchars($event['nom_evenement']) ?>"
                                                                            data-description="<?= htmlspecialchars($event['description_evenement']) ?>"
                                                                            data-date="<?= date('M d, Y', strtotime($event['date_evenement'])) ?>"
                                                                            data-lieu="<?= htmlspecialchars($event['nom_lieu']) ?>"
                                                                            data-place="<?= htmlspecialchars($event['place_evenement']) ?>"
                                                                            data-restantes="<?= htmlspecialchars($event['place_restantes']) ?>"
                                                                            data-type="<?= htmlspecialchars($event['type_evenement']) ?>">
                                                                            Afficher infos
                                                                        </button>
                                                                        <button class="btn btn-primary"
                                                                            onclick="window.location.href='ticket-details.php?id=<?= $event['id_evenement'] ?>'">
                                                                            Réserver ticket
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="thumb">
                                                                <?php 
                                                                    $image = !empty($event['image_evenement']) && file_exists(__DIR__ . "/../../assets/images/" . $event['image_evenement']) 
                                                                        ? $event['image_evenement'] 
                                                                        : 'default.jpg'; 
                                                                    ?>
                                                                    <img src="../../assets/images/<?= htmlspecialchars($image) ?>" alt="Event Image">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="right-content">
                                                                    <ul>
                                                                        <li><i class="fa fa-clock-o"></i>
                                                                            <h6><?=date('M d,Y',strtotime($event['date_evenement']))  ?></h6>
                                                                        </li>
                                                                        <li><i class="fa fa-users"></i>
                                                                            <span>Nombre de place total: <?=htmlspecialchars($event['place_evenement']) ?></span>
                                                                        </li>
                                                                        <li><i class="fa fa-map-marker"></i>
                                                                            <span>Lieu : <?=htmlspecialchars($event['nom_lieu']) ?></span>
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

                                <!-- ***** Past Events ***** -->
                                <article id='tabs-2'>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="heading"><h2>Événements passés</h2></div>
                                        </div>
                                        <?php if(!empty($pastEvents['Events'])) :?>
                                            <?php foreach($pastEvents['Events'] as $event):?>
                                                <div class="col-lg-12">
                                                    <div class="event-item">
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class='left-content'>
                                                                    <h4><?=htmlspecialchars($event['nom_evenement']) ?></h4>
                                                                    <p><?=htmlspecialchars($event['description_evenement']) ?></p>
                                                                    <div class="main-dark-button">
                                                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal" 
                                                                            data-id="<?= $event['id_evenement'] ?>"
                                                                            data-nom="<?= htmlspecialchars($event['nom_evenement']) ?>"
                                                                            data-description="<?= htmlspecialchars($event['description_evenement']) ?>"
                                                                            data-date="<?= date('M d, Y', strtotime($event['date_evenement'])) ?>"
                                                                            data-lieu="<?= htmlspecialchars($event['nom_lieu']) ?>"
                                                                            data-place="<?= htmlspecialchars($event['place_evenement']) ?>"
                                                                            data-restantes="<?= htmlspecialchars($event['place_restantes']) ?>"
                                                                            data-type="<?= htmlspecialchars($event['type_evenement']) ?>">
                                                                            Afficher plus
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="thumb">
                                                                    <?php 
                                                                    $image = !empty($event['image_evenement']) && file_exists(__DIR__ . "/../../assets/images/" . $event['image_evenement']) 
                                                                        ? $event['image_evenement'] 
                                                                        : 'default.jpg'; 
                                                                    ?>
                                                                    <img src="../../assets/images/<?= htmlspecialchars($image) ?>" alt="Event Image">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="right-content">
                                                                    <ul>
                                                                        <li><i class="fa fa-clock-o"></i>
                                                                            <h6><?=date('M d,Y',strtotime($event['date_evenement']))  ?></h6>
                                                                        </li>
                                                                        <li><i class="fa fa-users"></i>
                                                                            <span>Nombre de place total: <?=htmlspecialchars($event['place_evenement']) ?></span>
                                                                        </li>
                                                                        <li><i class="fa fa-map-marker"></i>
                                                                            <span>Lieu : <?=htmlspecialchars($event['nom_lieu']) ?></span>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else:?>
                                            <p>Pas d'événements</p>
                                        <?php endif;?>
                                    </div>
                                </article>

   

    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Détails de l'événement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 id="event-title"></h4>
                <p id="event-description"></p>
                <ul>
                    <li><strong>Date:</strong><span id="event-date"></span></li>
                    <li><strong>Lieu:</strong><span id="event-lieu"></span></li>
                    <li><strong>Type:</strong><span id="event-type"></span></li>
                    <li><strong>Places totales:</strong><span id="event-place"></span></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded",function(){
            var eventModal=document.getElementById('eventModal');
            eventModal.addEventListener('show.bs.modal',function(event){
                var button = event.relatedTarget;
                
                //recuperer les donnees stockes dans attribut data
                var nom=button.getAttribute('data-nom');
                var description=button.getAttribute('data-description');
                var date=button.getAttribute('data-date');
                var lieu=button.getAttribute('data-lieu');
                var place=button.getAttribute('data-place');
                var type=button.getAttribute('data-type');

                //remplir le modal avec les donnees de l'evenement
                document.getElementById('event-title').textContent=nom;
                document.getElementById('event-description').textContent=description;
                document.getElementById('event-date').textContent=date;
                document.getElementById('event-lieu').textContent=lieu;
                document.getElementById('event-place').textContent=place;
                document.getElementById('event-type').textContent=type;

            });
        });
    </script>

    <!-- jQuery -->
    <script src="../../assets/js/jquery-2.1.0.min.js"></script>

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
    .modal-body ul {
    list-style: none;
    padding-left: 0;
    margin-top: 15px;
}

.modal-body ul li {
    margin-bottom: 12px;
    display: block;
    width: 100%;
}

.modal-body ul li strong {
    display: block;
    font-weight: 600;
    margin-bottom: 3px;
}

.modal-body ul li span {
    display: block;
    padding-left: 10px;
}

.modal-title {
    color: #1e1e1e;
    font-weight: 600;
}

#event-title {
    color: #f35525;
    margin-bottom: 15px;
}
</style>
</html>