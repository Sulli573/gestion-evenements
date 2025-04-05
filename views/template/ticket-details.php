<?php
require_once __DIR__ . '/../../config/DatabaseManager.php';
require_once __DIR__ . '/../../config/loadEnv.php';
require_once __DIR__ . '/../../config/sessionManager.php';

loadEnv(__DIR__ . '/../../.env');

$database = new DatabaseManager(
    $_ENV['DB_HOST'],
    (int) $_ENV['DB_PORT'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

if(!isset($_GET['id']) && empty($_GET['id'])){
    die("Id evenement non fourni");
}

$id_event=intval($_GET['id']);
$apiUrl="http://localhost/PHP2/api/events/$id_event";
$response=file_get_contents($apiUrl);
$response = json_decode($response, true);
$event=$response['event'];

$id_lieu=$event['id_lieu'];
$apiUrl="http://localhost/PHP2/api/lieux/$id_lieu";
$response=file_get_contents($apiUrl);
$response = json_decode($response, true);
$lieu=$response['lieu'][0];

if(!isset($event['event']) || empty($event['event'])) {
    #Au lieu d'afficher les erreurs on affichera un tableau vide à l'utilisateur
    $event['event'] = [];
}

if(!isset($lieu['lieu']) || empty($lieu['lieu'])) {
    #Au lieu d'afficher les erreurs on affichera un tableau vide à l'utilisateur
    $lieu['lieu'] = [];
}

$dateEvent=new DateTime($event['date_evenement']);
$heureDebut=new DateTime($event['heure_debut']);
$heureFin=new DateTime($event['heure_fin']);

$formatedDay=$dateEvent->format('l');//Monday,Tuesday,Wednesday
$formatedStart=$heureDebut->format('H:i');//L'horaire va etre presenter en heure et minute
$formatedEnd=$heureFin->format('H:i');
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Tooplate">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">

    <title>ArtXibition Ticket Detail Page</title>


    <!-- Additional CSS Files -->
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
    
    <!-- ***** Pre HEader ***** -->
    <div class="pre-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-sm-6">
                    <span>Hey! The Show Is Starting In 5 Days.</span>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <div class="text-button">
                        <a href="rent-venue.html">Contact Us Now! <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.html" class="logo">Art<em>Xibition</em></a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li><a href="index.html">Home</a></li>
                            <li><a href="about.html">About Us</a></li>
                            <li><a href="rent-venue.html">Rent Venue</a></li>
                            <li><a href="shows-events.html">Shows & Events</a></li> 
                            <li><a href="tickets.html" class="active">Tickets</a></li> 
                        </ul>        
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <!-- ***** About Us Page ***** -->
    <div class="page-heading-shows-events">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Tickets On Sale Now!</h2>
                    <span>Check out upcoming and past shows & events and grab your ticket right now.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="ticket-details-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="left-image">
                        <img src="../../assets/images/ticket-details.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="right-content">
                        <h4><?= htmlspecialchars($event['nom_evenement'])?></h4>
                        <span><?= htmlspecialchars($event['place_restantes'])?> Tickets still available</span>
                        <ul>
                            <li><i class="fa fa-clock-o"></i><?= htmlspecialchars($formatedDay)?>,  <?= htmlspecialchars($formatedStart)?> to <?= htmlspecialchars($formatedEnd)?></li>
                            <li><i class="fa fa-map-marker"></i><?= htmlspecialchars($lieu['nom_lieu'])?>,<?= htmlspecialchars($lieu['adresse_lieu'])?></li>
                        </ul>
                        <div class="quantity-content">
                            <div class="left-content">
                                <h6>Standard Ticket</h6>
                                <p><?= htmlspecialchars($event['prix_evenement'])?>€ per ticket</p>
                            </div>
                            <div class="right-content">
                                <div class="quantity buttons_added">
                                    <input type="button" value="-" class="minus">
                                    <input type="number" step="1" min="1" max="5" name="quantity" value="1" title="Qty" class="input-text qty text" id="ticket-quantity">
                                    <input type="button" value="+" class="plus">
                                </div>
                            </div>
                        </div>
                        <div class="total">
                            <h4>Total: <span id="total-price" style="display: inline; font-size:inherit; font-weight:inherit; color:inherit;"><?= htmlspecialchars($event['prix_evenement'])?></span>€</h4>
                            <div class="main-dark-button"><a href="#">Purchase Tickets</a></div>
                        </div>
                        <div class="warn">
                            <p>*You Can Only Buy 5 Tickets For This Show</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- *** Subscribe *** -->
    <div class="subscribe">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <h4>Subscribe Our Newsletter:</h4>
                </div>
                <div class="col-lg-8">
                    <form id="subscribe" action="" method="get">
                        <div class="row">
                          <div class="col-lg-9">
                            <fieldset>
                              <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email Address" required="">
                            </fieldset>
                          </div>
                          <div class="col-lg-3">
                            <fieldset>
                              <button type="submit" id="form-submit" class="main-dark-button">Submit</button>
                            </fieldset>
                          </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
    
    

    <!-- jQuery -->
    <script src="../../assets/js/jquery-2.1.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="../../assets/js/popper.js"></script>
    <script src="../../assets/js/bootstrap.min.js"></script>

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
    <!-- fonction pour mettre a jour le prix total -->
    <script>
        document.addEventListener("DOMContentLoaded",function(){
            const quantityInput = document.getElementById("ticket-quantity");
            const ticketPrice = parseFloat(document.getElementById("total-price").textContent);
            const totalPriceElement = document.getElementById("total-price");

            const plusButton = document.querySelector(".plus");
            const minusButton = document.querySelector(".minus");

            function updateTotal(){
                let quantity = parseInt(quantityInput.value);
                
                if(isNaN(quantity) || quantity<1){
                    quantity = 1;
                }
                if(quantity>5){
                    quantity=5;
                }
                quantityInput.value = quantity;

                let total=quantity*ticketPrice;
                totalPriceElement.textContent=total.toFixed(2);
            }

            updateTotal();

            plusButton.addEventListener("click",function(){
                let quantity = parseInt(quantityInput.value);
                if(quantity<5){
                    quantityInput.value=quantity + 1;
                    updateTotal();
                }
            });
            minusButton.addEventListener("click",function(){
                let quantity = parseInt(quantityInput.value);
                if(quantity>1){
                    quantityInput.value=quantity - 1;
                    updateTotal();
                }
            });

            quantityInput.addEventListener("change",function(){
                updateTotal();
            });
        });
    </script>
  </body>

</html>

