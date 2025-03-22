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
                            <h5 class="card-header">Détails des Événements</h5>
                            <div class="table-responsive text-start">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Événement</th>
                                            <th>Description</th>
                                            <th>Organisateur</th>
                                            <th>Places totales</th>
                                            <th>Places restantes</th>
                                            <th>Catégorie</th>
                                            <th>Lieu</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($allEvents['events'] as $event): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($event['nom_evenement']) ?></td>
                                            <td><?= htmlspecialchars($event['description_evenement']) ?></td>
                                            <td><?= htmlspecialchars($event['nom_organisateur']) ?></td>
                                            <td><?= htmlspecialchars($event['place_evenement']) ?></td>
                                            <td><?= htmlspecialchars($event['place_restantes']) ?></td>
                                            <td><?= htmlspecialchars($event['type_evenement']) ?></td>
                                            <td><?= htmlspecialchars($event['nom_lieu']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($event['date_evenement'])) ?></td>
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
                                                    data-date="<?= htmlspecialchars($event['date_evenement']) ?>">
                                                    <i class="bx bx-edit-alt me-1"></i> Modifier
                                                </button>

                                                <button type="button" class="btn btn-danger delete-btn"
                                                    data-id="<?= $lieu['id'] ?>">
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
    });
</script>

</body>
</html>
<!-- jQuery (nécessaire pour Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
