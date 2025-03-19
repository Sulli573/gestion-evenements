<?php
require_once __DIR__ . '/../../config/DatabaseManager.php';
require_once __DIR__ . '/../../config/loadEnv.php';
require_once __DIR__ . '/../../models/EvenementsModel.php';
require_once __DIR__ . '/../../controller/EvenementController.php';

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
    <title>Event Artisanat</title>
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
    <header class="header-area header-sticky">
        <div class="container">
            <nav class="main-nav">
                <a href="index.html" class="logo">Art<em>Xibition</em></a>
                <ul class="nav">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="rent-venue.html">Rent Venue</a></li>
                    <li><a href="shows-events.php" class="active">Shows & Events</a></li> 
                    <li><a href="tickets.html">Tickets</a></li> 
                </ul>        
            </nav>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <!-- ***** About Us Page ***** -->
    <div class="page-heading-shows-events">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Our Shows & Events</h2>
                    <span>Check out upcoming and past shows & events.</span>
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
                                          <li><a href='#tabs-1'>Upcoming</a></li>
                                          <li><a href='#tabs-2'>Past</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4">
                                          <div class="main-dark-button">
                                              <a href="ticket-details.html">Purchase Tickets</a>
                                          </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <section class='tabs-content'>
                                <article id='tabs-1'>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="heading"><h2>Upcoming Events</h2></div>
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
                                                                            Afficher les détails
                                                                        </button>
                                                                        <button class="btn btn-primary" style="margin-top: 10px"
                                                                        onclick="window.location.href='ticket-details.php?id=<?= $event['id_evenement'] ?>'"> 
                                                                            Reserver un ticket
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
                                            <p>No Upcoming Events</p>
                                        <?php endif;?>
                                    </div>
                                </article>

                                <!-- ***** Past Events ***** -->
                                <article id='tabs-2'>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="heading"><h2>Past Events</h2></div>
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
                                                                            Discover More
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
                                            <p>No past events found.</p>
                                        <?php endif;?>
                                    </div>
                                </article>

    <!-- *** Footer *** -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="address">
                        <h4>Sunny Hill Festival Address</h4>
                        <span>5 College St NW, <br>Norcross, GA 30071<br>United States</span>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="links">
                        <h4>Useful Links</h4>
                        <ul>
                            <li><a href="#">Info</a></li>
                            <li><a href="#">Venues</a></li>
                            <li><a href="#">Guides</a></li>
                            <li><a href="#">Videos</a></li>
                            <li><a href="#">Outreach</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="hours">
                        <h4>Open Hours</h4>
                        <ul>
                            <li>Mon to Fri: 10:00 AM to 8:00 PM</li>
                            <li>Sat - Sun: 11:00 AM to 4:00 PM</li>
                            <li>Holidays: Closed</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="under-footer">
                        <div class="row">
                            <div class="col-lg-6">
                                <p>São Conrado, Rio de Janeiro</p>
                            </div>
                            <div class="col-lg-6">
                                <p class="copyright">Copyright 2021 ArtXibition Company 
                    
                    			<br>Design: <a rel="nofollow" href="https://www.tooplate.com" target="_parent">Tooplate</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="sub-footer">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="logo"><span>Art<em>Xibition</em></span></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="menu">
                                    <ul>
                                        <li><a href="index.html" class="active">Home</a></li>
                                        <li><a href="about.html">About Us</a></li>
                                        <li><a href="rent-venue.html">Rent Venue</a></li>
                                        <li><a href="shows-events.html">Shows & Events</a></li> 
                                        <li><a href="tickets.html">Tickets</a></li> 
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="social-links">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa fa-behance"></i></a></li>
                                        <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

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
                    <li><strong>Places disponibles:</strong><span id="event-restantes"></span></li>
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
                
                //recuperer les donnees stockes dant attribut data
                var nom=button.getAttribute('data-nom');
                var description=button.getAttribute('data-description');
                var date=button.getAttribute('data-date');
                var lieu=button.getAttribute('data-lieu');
                var place=button.getAttribute('data-place');
                var restantes=button.getAttribute('data-restantes');
                var type=button.getAttribute('data-type');

                //remplir le modal avec les donnees de l'evenement
                document.getElementById('event-title').textContent=nom;
                document.getElementById('event-description').textContent=description;
                document.getElementById('event-date').textContent=date;
                document.getElementById('event-lieu').textContent=lieu;
                document.getElementById('event-place').textContent=place;
                document.getElementById('event-restantes').textContent=restantes;
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
    .modal-body ul li span{
        display:block;
        margin-top: 3px;
    }
</style>
</html>
