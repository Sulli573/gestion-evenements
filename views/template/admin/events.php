<?php
require_once __DIR__ . '/../../../config/DatabaseManager.php';
require_once __DIR__ . '/../../../config/loadEnv.php';
require_once __DIR__ . '/../../../models/EvenementsModel.php';
require_once __DIR__ . '/../../../models/LieuModel.php';
require_once __DIR__ . '/../../../models/OrganisateurModel.php';
require_once __DIR__ . '/../../../controller/EvenementController.php';
require_once __DIR__ . '/../../../controller/LieuController.php';
require_once __DIR__ . '/../../../controller/OrganisateurController.php';
require_once __DIR__ . '/../../../config/sessionManager.php';
require_once __DIR__ . '/../../partial/navbar.php';

loadEnv(__DIR__ . '/../../../.env');

$database = new DatabaseManager(
    $_ENV['DB_HOST'],
    (int) $_ENV['DB_PORT'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

$apiUrl="http://localhost/PHP2/api/events";
$response=file_get_contents($apiUrl);
// Récupération des données
$allEvents = json_decode($response, true);

$apiUrl="http://localhost/PHP2/api/lieux";
$response=file_get_contents($apiUrl);
$allLieux = json_decode($response, true);

$apiUrl="http://localhost/PHP2/api/organisateurs";
$response=file_get_contents($apiUrl);
$allOrganisateurs = json_decode($response, true);

if (!isset($allEvents['events']) || empty($allEvents['events'])) {
    $allEvents['events'] = [];
}
if (!isset($allOrganisateurs['organisateurs']) || empty($allOrganisateurs['organisateurs'])) {
    $allOrganisateurs['organisateurs'] = [];
}
if (!isset($allLieux['lieux']) || empty($allLieux['lieux'])) {
    $allLieux['lieux'] = [];
}
?>

<!doctype html>
<html lang="fr" class="light-style layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Gestion des Événements</title>
    <meta name="description" content="" />

    <!-- Styles -->
    <link rel="stylesheet" href="../../../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../../../assets/vendor/css/core.css" />
    <link rel="stylesheet" href="../../../assets/vendor/css/theme-default.css" />
    <link rel="stylesheet" href="../../../assets/css/demo.css" />
    <link rel="stylesheet" href="../../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Helpers -->
    <script src="../../../assets/vendor/js/helpers.js"></script>
    <script src="../../../assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="container-fluid text-start">
                        <hr class="my-12" />

                        <!-- Tableau des événements -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Détails des Événements</h5>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                    <i class="bi bi-plus-circle" style="margin-right: 5px;"></i> Ajouter
                                </button>
                            </div>
                            <div class="table-responsive text-start">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Événement</th>
                                            <th>Description</th>
                                            <th>Heure de début</th>
                                            <th>Heure de fin</th>
                                            <th>Prix ticket</th>
                                            <th>Organisateur</th>
                                            <th>Places totales</th>
                                            <th>Places restantes</th>
                                            <th>Catégorie</th>
                                            <th>Lieu</th>
                                            <th>Date</th>
                                            <th>Image</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($allEvents['events'] as $event): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($event['nom_evenement']) ?></td>
                                            <td><?= htmlspecialchars($event['description_evenement']) ?></td>
                                            <td><?= htmlspecialchars($event['heure_debut']) ?></td>
                                            <td><?= htmlspecialchars($event['heure_fin']) ?></td>
                                            <td><?= htmlspecialchars($event['prix_evenement']) ?></td>
                                            <td><?= htmlspecialchars($event['nom_organisateur']) ?></td>
                                            <td><?= htmlspecialchars($event['place_evenement']) ?></td>
                                            <td><?= htmlspecialchars($event['place_restantes']) ?></td>
                                            <td><?= htmlspecialchars($event['type_evenement']) ?></td>
                                            <td><?= htmlspecialchars($event['nom_lieu']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($event['date_evenement'])) ?></td>
                                            <td><?= htmlspecialchars($event['image_evenement']) ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editEventModal"
                                                    data-id="<?= $event['id_evenement'] ?>"
                                                    data-nom="<?= htmlspecialchars($event['nom_evenement']) ?>"
                                                    data-description="<?= htmlspecialchars($event['description_evenement']) ?>"
                                                    data-organisateur="<?= $event['id_organisateur'] ?>"
                                                    data-places-totales="<?= $event['place_evenement'] ?>"
                                                    data-places-restantes="<?= $event['place_restantes'] ?>"
                                                    data-categorie="<?= htmlspecialchars($event['type_evenement']) ?>"
                                                    data-lieu="<?= $event['id_lieu'] ?>"
                                                    data-date="<?= htmlspecialchars($event['date_evenement']) ?>"
                                                    data-heured="<?= htmlspecialchars($event['heure_debut']) ?>"
                                                    data-heuref="<?= htmlspecialchars($event['heure_fin']) ?>"
                                                    data-prix="<?= htmlspecialchars($event['prix_evenement']) ?>"
                                                    data-image="<?= htmlspecialchars($event['image_evenement']) ?>">
                                                    <i class="bx bx-edit-alt me-1"></i> Modifier
                                                </button>

                                                <button type="button" class="btn btn-danger delete-btn"
                                                    data-id="<?= $event['id_evenement'] ?>">
                                                    <i class="bx bx-trash me-1"></i> Supprimer
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr class="my-12" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modifier Événement -->
     <div class="modal fade" id="editEventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier l'Événement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editEventForm" method="POST">
                        <input type="hidden" id="edit_id" name="id_evenement">

                        <div class="mb-3">
                            <label for="edit_nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="edit_nom" name="nom_evenement" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description_evenement"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit_heured" class="form-label">Heure de début</label>
                            <input type="time" class="form-control" id="edit_heured" name="heure_debut" required>
                        </div>                        
                        
                        <div class="mb-3">
                            <label for="edit_heuref" class="form-label">Heure de fin</label>
                            <input type="time" class="form-control" id="edit_heuref" name="heure_fin" required>
                        </div> 

                        <div class="mb-3">
                            <label for="edit_prix" class="form-label">Prix de ticket</label>
                            <input type="number" class="form-control" id="edit_prix" name="prix_evenement" required>
                        </div>  

                        <div class="mb-3">
                            <label for="edit_organisateur" class="form-label">Organisateur</label>
                            <select class="form-control" id="edit_organisateur" name="id_organisateur">
                                <?php foreach ($allOrganisateurs['organisateurs'] as $org): ?>
                                    <option value="<?= $org['id'] ?>"><?= htmlspecialchars($org['nom_organisateur']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_places_totales" class="form-label">Places Totales</label>
                            <input type="number" class="form-control" id="edit_places_totales" name="place_evenement">
                        </div>

                        <div class="mb-3">
                            <label for="edit_places_restantes" class="form-label">Places Restantes</label>
                            <input type="number" class="form-control" id="edit_places_restantes" name="place_restantes">
                        </div>

                        <div class="mb-3">
                            <label for="edit_categorie" class="form-label">Catégorie</label>
                            <input type="text" class="form-control" id="edit_categorie" name="type_evenement">
                        </div>

                        <div class="mb-3">
                            <label for="edit_lieu" class="form-label">Lieu</label>
                            <select class="form-control" id="edit_lieu" name="id_lieu">
                                <?php foreach ($allLieux['lieux'] as $lieu): ?>
                                    <option value="<?= $lieu['id'] ?>"><?= htmlspecialchars($lieu['nom_lieu']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="edit_date" name="date_evenement" required>
                        </div>

                        <div class="mb-3">
                            <label for="add_image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="add_image" name="image_evenement">
                        </div>

                        <button type="submit" class="btn btn-primary">Sauvegarder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

     <!-- Modal Ajouter Événement -->
     <div class="modal fade" id="addEventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter l'Événement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addEventForm" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label for="add_nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="add_nom" name="nom_evenement" required>
                        </div>

                        <div class="mb-3">
                            <label for="add_description" class="form-label">Description</label>
                            <textarea class="form-control" id="add_description" name="description_evenement"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="add_heured" class="form-label">Heure de début</label>
                            <input type="time" class="form-control" id="add_heured" name="heure_debut" required>
                        </div>                        
                        
                        <div class="mb-3">
                            <label for="add_heuref" class="form-label">Heure de fin</label>
                            <input type="time" class="form-control" id="add_heuref" name="heure_fin" required>
                        </div> 

                        <div class="mb-3">
                            <label for="add_prix" class="form-label">Prix de ticket</label>
                            <input type="number" class="form-control" id="add_prix" name="prix_evenement" required>
                        </div>                        

                        <div class="mb-3">
                            <label for="add_organisateur" class="form-label">Organisateur</label>
                            <select class="form-control" id="add_organisateur" name="id_organisateur">
                                <?php foreach ($allOrganisateurs['organisateurs'] as $org): ?>
                                    <option value="<?= $org['id'] ?>"><?= htmlspecialchars($org['nom_organisateur']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="add_places_totales" class="form-label">Places Totales</label>
                            <input type="number" class="form-control" id="add_places_totales" name="place_evenement">
                        </div>

                        <div class="mb-3">
                            <label for="add_categorie" class="form-label">Catégorie</label>
                            <input type="text" class="form-control" id="add_categorie" name="type_evenement">
                        </div>

                        <div class="mb-3">
                            <label for="add_lieu" class="form-label">Lieu</label>
                            <select class="form-control" id="add_lieu" name="id_lieu">
                                <?php foreach ($allLieux['lieux'] as $lieu): ?>
                                    <option value="<?= $lieu['id'] ?>"><?= htmlspecialchars($lieu['nom_lieu']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="add_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="add_date" name="date_evenement" required>
                        </div>

                        <div class="mb-3">
                            <label for="add_image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="add_image" name="image_evenement">
                        </div>

                        <button type="submit" class="btn btn-primary">Sauvegarder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        // Sélectionne tous les boutons Modifier (edit-btn)
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function () {
                // Remplit les champs du modal avec les données de l'événement sélectionné
                document.getElementById("edit_id").value = this.getAttribute("data-id");
                document.getElementById("edit_nom").value = this.getAttribute("data-nom");
                document.getElementById("edit_description").value = this.getAttribute("data-description");
                document.getElementById("edit_places_totales").value = this.getAttribute("data-places-totales");
                document.getElementById("edit_places_restantes").value = this.getAttribute("data-places-restantes");
                document.getElementById("edit_categorie").value = this.getAttribute("data-categorie");
                document.getElementById("edit_heured").value = this.getAttribute("data-heured");
                document.getElementById("edit_heuref").value = this.getAttribute("data-heuref");
                document.getElementById("edit_prix").value = this.getAttribute("data-prix");
                let fullDate = this.getAttribute("data-date"); 
                let formattedDate = fullDate.split(" ")[0]; // Sépare "2025-06-15 00:00:00" et garde "2025-06-15"
                document.getElementById("edit_date").value = formattedDate;

                // Sélectionne le bon organisateur dans la liste déroulante
                let selectOrganisateur = document.getElementById("edit_organisateur");
                let idOrganisateur = this.getAttribute("data-organisateur");
                for (let option of selectOrganisateur.options) {
                    if (option.value === idOrganisateur) {
                        option.selected = true;
                        break;
                    }
                }

                // Sélectionne le bon lieu dans la liste déroulante
                let selectLieu = document.getElementById("edit_lieu");
                let idLieu = this.getAttribute("data-lieu");
                for (let option of selectLieu.options) {
                    if (option.value === idLieu) {
                        option.selected = true;
                        break;
                    }
                }
            });
        });
    });
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Intercepter la soumission du formulaire
        document.getElementById("editEventForm").addEventListener("submit", function (event) {
            event.preventDefault(); // Empêcher le rechargement de la page

            let formData = new FormData(this); // Récupérer les données du formulaire

            formData.append("csrf_token", "<?= $_SESSION['csrf_token'] ?? '' ?>");

            fetch('/PHP2/api/event/update', {
                method: "POST",
                body: formData,
                headers:{
                    "Accept":"application/json"
                }
            })
            .then(response => response.json()) // Convertir la réponse en JSON
            .then(data => {
                if (data.status === "success") {
                    alert("Mise à jour réussie !");
                    location.reload(); 
                } else {
                    alert("Erreur : " + data.message); // Afficher un message d'erreur
                }
            })
            .catch(error => console.error("Erreur:", error));
        });

        document.getElementById("addEventForm").addEventListener("submit", function (event) {
          event.preventDefault();

          let formData = new FormData(this);
          formData.append("csrf_token", "<?= $_SESSION['csrf_token'] ?? '' ?>");
          
          fetch('/PHP2/api/event/create', {
                method: "POST",
                body: formData,
                headers:{
                    "Accept":"application/json"
                }
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

        // Suppression d'un evenement
    document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                let eventId = this.getAttribute("data-id");

                if (confirm("Voulez-vous vraiment supprimer cet évenement ?")) {
                    fetch("/PHP2/api/event/delete", {
                        method: "POST",
                        body: JSON.stringify({ id_evenement: eventId, csrf_token: "<?= $_SESSION['csrf_token'] ?>" }),
                        headers: { "Content-Type": "application/json" }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.status === "success") {
                            location.reload();
                        }
                    })
                    .catch(error => console.error("Erreur:", error));
                }
            });
        });
    });

   
</script>

<style>
    body {
      text-align: left !important;
      margin-left: 25px;
      margin-right: 25px;
    }

    .container-fluid {
      margin-left: 0 !important;
      padding: 0 !important;
    }

    .container-xxl, .layout-container, .layout-page, .content-wrapper {
      margin: 0 !important;
      padding: 0 !important;
    }
    </style>

</body>
</html>
<!-- jQuery (nécessaire pour Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>