<?php
require_once __DIR__ . '/../../config/DatabaseManager.php';
require_once __DIR__ . '/../../config/loadEnv.php';
require_once __DIR__ . '/../../config/sessionManager.php';
require_once __DIR__ . '/../../middlewares/authUtilisateur.php';
require_once __DIR__. '/../partial/usernavbar.php';

loadEnv(__DIR__ . '/../../.env');

$database = new DatabaseManager(
    $_ENV['DB_HOST'],
    (int) $_ENV['DB_PORT'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

if (!isset($_GET['id']) && empty($_GET['id'])) {
    die("Id evenement non fourni");
}

$id_event = intval($_GET['id']);
$apiUrl = "http://localhost/PHP2/api/events/$id_event";
$response = file_get_contents($apiUrl);
$response = json_decode($response, true);
$event = $response['event'];

$id_lieu = $event['id_lieu'];
$apiUrl = "http://localhost/PHP2/api/lieux/$id_lieu";
$response = file_get_contents($apiUrl);
$response = json_decode($response, true);
$lieu = $response['lieu'][0];

if (!isset($event['event']) || empty($event['event'])) {
    #Au lieu d'afficher les erreurs on affichera un tableau vide à l'utilisateur
    $event['event'] = [];
}

if (!isset($lieu['lieu']) || empty($lieu['lieu'])) {
    #Au lieu d'afficher les erreurs on affichera un tableau vide à l'utilisateur
    $lieu['lieu'] = [];
}

$dateEvent = new DateTime($event['date_evenement']);
$heureDebut = new DateTime($event['heure_debut']);
$heureFin = new DateTime($event['heure_fin']);

$formatedDay = $dateEvent->format('l'); //Monday,Tuesday,Wednesday
$formatedStart = $heureDebut->format('H:i'); //L'horaire va etre presenter en heure et minute
$formatedEnd = $heureFin->format('H:i');
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

    <!-- ***** About Us Page ***** -->
    <div class="page-heading-shows-events">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Réservez vos tickets</h2>
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
                        <h4><?= htmlspecialchars($event['nom_evenement']) ?></h4>
                        <span><?= htmlspecialchars($event['place_restantes']) ?> Tickets encore disponibles</span>
                        <ul>
                            <li><i class="fa fa-clock-o"></i><?= htmlspecialchars($formatedDay) ?>, <?= htmlspecialchars($formatedStart) ?> to <?= htmlspecialchars($formatedEnd) ?></li>
                            <li><i class="fa fa-map-marker"></i><?= htmlspecialchars($lieu['nom_lieu']) ?>,<?= htmlspecialchars($lieu['adresse_lieu']) ?></li>
                        </ul>
                        <div class="quantity-content">
                            <div class="left-content">
                            
                                <p><?= htmlspecialchars($event['prix_evenement']) ?>€ par ticket</p>
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
                            <h4>Total: <span id="total-price" style="display: inline; font-size:inherit; font-weight:inherit; color:inherit;"><?= htmlspecialchars($event['prix_evenement']) ?></span>€</h4>
                            <div class="main-dark-button"><a href="#" id="purchase-btn">Acheter ticket(s)</a></div>
                        </div>
                        <div class="warn">
                            <p>*Vous ne pouvez réserver que 5 tickets maximum</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        document.addEventListener("DOMContentLoaded", function() {
            const quantityInput = document.getElementById("ticket-quantity");
            const ticketPrice = parseFloat(document.getElementById("total-price").textContent);
            const totalPriceElement = document.getElementById("total-price");

            const plusButton = document.querySelector(".plus");
            const minusButton = document.querySelector(".minus");

            function updateTotal() {
                let quantity = parseInt(quantityInput.value);

                if (isNaN(quantity) || quantity < 1) {
                    quantity = 1;
                }
                if (quantity > 5) {
                    quantity = 5;
                }
                quantityInput.value = quantity;

                let total = quantity * ticketPrice;
                totalPriceElement.textContent = total.toFixed(2);
            }

            updateTotal();

            plusButton.addEventListener("click", function() {
                let quantity = parseInt(quantityInput.value);
                if (quantity < 5) {
                    quantityInput.value = quantity + 1;
                    updateTotal();
                }
            });
            minusButton.addEventListener("click", function() {
                let quantity = parseInt(quantityInput.value);
                if (quantity > 1) {
                    quantityInput.value = quantity - 1;
                    updateTotal();
                }
            });

            quantityInput.addEventListener("change", function() {
                updateTotal();
            });
        });

            //Inscription à un événement
        const userId = <?= json_encode($_SESSION['id_utilisateur'] ?? null) ?>;
        const eventId = <?= json_encode($id_event) ?>;
        const csrfToken = <?= json_encode($_SESSION['csrf_token']) ?>;

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("purchase-btn").addEventListener("click", function(e) {
                e.preventDefault();

                const quantity = parseInt(document.getElementById("ticket-quantity").value);
                if (!userId || !eventId || !csrfToken) {
                    alert("Une erreur est survenue, merci de vous reconnecter");
                    return;
                }

                fetch('/PHP2/api/inscrire/create', {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: new URLSearchParams({
                            id_user:userId,
                            id_event: eventId,
                            nbr_ticket: quantity,
                            csrf_token: csrfToken
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.status === "success") {
                            location.reload();
                        }
                    })
                    .catch(error => console.error("Erreur:", error));
            });
        });
    </script>
</body>

</html>