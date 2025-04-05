<?php
require_once __DIR__ . '/../../../config/DatabaseManager.php';
require_once __DIR__ . '/../../../config/loadEnv.php';
require_once __DIR__ . '/../../../models/LieuModel.php';
require_once __DIR__ . '/../../../controller/LieuController.php';
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

$apiUrl="http://localhost/PHP2/api/lieux";
$response=file_get_contents($apiUrl);
$allLieux = json_decode($response, true);


if(!isset($allLieux['lieux']) || empty($allLieux['lieux'])) {
  #Au lieu d'afficher les erreurs on affichera un tableau vide à l'administrateur
  $allLieux['lieux'] = [];
  
}
?>

<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../../assets/"
  data-template="vertical-menu-template-free"
  data-style="light">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Demo : Tables - Basic Tables | sneat - Bootstrap Dashboard PRO</title>

    <meta name="description" content="" />


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="../../../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../../assets/css/demo.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../../../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
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
            <!-- Content -->

            <div class="container-fluid text-start">
              <hr class="my-12" />

              <!-- Bootstrap Table with Header - Light -->
              <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                  <h5 class="mb-0">Détails Lieux</h5>
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLieuModal">
                      <i class="bi bi-plus-circle" style="margin-right : 5px"></i> Ajouter
                  </button>
              </div>
                
                <div class="table-responsive text-start">
                  <table class="table">
                    <thead class="table-light">
                      <tr>
                        <th>id_lieu</th>
                        <th>nom_lieu</th>
                        <th>adresse_lieu</th>
                        <th>actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php var_dump($response);?>
                      <!--Contenu des colonnes va créer une ligne par événement avec valeurs dans td-->
                      <?php foreach($allLieux['lieux'] as $lieu) :?> 
                        <tr>
                          <td><?=htmlspecialchars($lieu['id'])?></td>
                          <td><?=htmlspecialchars($lieu['nom_lieu'])?></td>
                          <td><?=htmlspecialchars($lieu['adresse_lieu'])?></td>
                        <td>
                             <!-- Bouton Edit pour ouvrir le modal -->
                             <button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editLieuModal"
                                    data-id="<?= $lieu['id'] ?>"
                                    data-nom="<?= htmlspecialchars($lieu['nom_lieu']) ?>"
                                    data-adresse="<?= htmlspecialchars($lieu['adresse_lieu']) ?>">
                                    <i class="bx bx-edit-alt me-1"></i> Modifier
                                </button>

                                <!-- Bouton Delete -->
                                <button type="button" class="btn btn-danger delete-btn"
                                    data-id="<?= $lieu['id'] ?>">
                                    <i class="bx bx-trash me-1"></i> Supprimer
                                </button>
                          </td>

                      </tr>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- Bootstrap Table with Header - Light -->

              <hr class="my-12" />

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- Model Modifier Lieu -->
<div class="modal fade" id="editLieuModal" tabindex="-1" aria-labelledby="editLieuLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLieuLabel">Modifier le lieu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editLieuForm">
                    <input type="hidden" id="edit_id" name="id_lieu">
                    <input type="hidden" id="csrf_token" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <div class="mb-3">
                        <label for="edit_nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="edit_nom" name="nom_lieu" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="edit_adresse" name="adresse_lieu" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- Modal Ajouter Lieu -->
<div class="modal fade" id="addLieuModal" tabindex="-1" aria-labelledby="addLieuLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLieuLabel">Ajouter le lieu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addLieuForm">
                    <input type="hidden" id="csrf_token_add" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <div class="mb-3">
                        <label for="add_nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="add_nom" name="nom_lieu" required>
                    </div>

                    <div class="mb-3">
                        <label for="add_adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="add_adresse" name="adresse_lieu" required>
                    </div>

                    <button type="submit" class="btn btn-success">Sauvegarder</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="../../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
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

    
</html>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Récupérer les boutons Edit et mettre à jour les valeurs du model
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("edit_id").value = this.getAttribute("data-id");
                document.getElementById("edit_nom").value = this.getAttribute("data-nom");
                document.getElementById("edit_adresse").value = this.getAttribute("data-adresse");
            });
        });

        // Gestion de la soumission du formulaire Update
        document.getElementById("editLieuForm").addEventListener("submit", function (lieu) {
            lieu.preventDefault();
            
            let formData = new FormData(this);
            formData.append("csrf_token", "<?= $_SESSION['csrf_token'] ?? '' ?>");
            
            fetch('/PHP2/api/lieux/update', {
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

        // Suppression d'un lieu
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                let lieuId = this.getAttribute("data-id");

                if (confirm("Voulez-vous vraiment supprimer ce lieu ?")) {
                    fetch("/PHP2/api/lieux/delete", {
                        method: "POST",
                        body: JSON.stringify({ id: lieuId, csrf_token: "<?= $_SESSION['csrf_token'] ?>" }),
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
        //Ajouter un lieu
        document.getElementById("addLieuForm").addEventListener("submit", function (event) {
          event.preventDefault();

          let formData = new FormData(this);
          formData.append("csrf_token", "<?= $_SESSION['csrf_token'] ?? '' ?>");
          
          fetch('/PHP2/api/lieux/add', {
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
        });

</script>