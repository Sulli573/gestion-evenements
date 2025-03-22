
<?php
require_once __DIR__ . '/../../../config/DatabaseManager.php';
require_once __DIR__ . '/../../../config/loadEnv.php';
require_once __DIR__ . '/../../../models/UserModel.php';
require_once __DIR__ . '/../../../controller/UserController.php';


loadEnv(__DIR__ . '/../../../.env');

$database = new DatabaseManager(
    $_ENV['DB_HOST'],
    (int) $_ENV['DB_PORT'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

$apiUrl="http://localhost/PHP2/api/users";
$response=file_get_contents($apiUrl);
$allUsers = json_decode($response, true);

if(!isset($allUsers['users']) || empty($allUsers['users'])) {
  #Au lieu d'afficher les erreurs on affichera un tableau vide à l'administrateur
  $allUsers['users'] = [];
}

?>
<!doctype html>
<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
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

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../assets/js/config.js"></script>
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
                <h5 class="card-header">Détails Utilisateurs</h5>
                <div class="table-responsive text-start">
                  <table class="table">
                    <thead class="table-light">
                      <tr>
                        <th>User</th>
                        <th>id de l'utilisateur</th>
                        <th>nom de l'utilisateur</th>
                        <th>est actif</th>
                        <th>est suspendu</th>
                        <th>motif suspension</th>
                        <th>role de l'utilisateur</th>
                        <th>actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--Contenu des colones va créer une ligne par événement avec valeurs dans td-->
                      <?php foreach($allUsers['users'] as $user) :?> 
                        <tr>
                          <td><?=htmlspecialchars($user['id_utilisateur'])?></td>
                          <td><?=htmlspecialchars($user['nom_utilisateur'])?></td>
                          <td><?=htmlspecialchars($user['courriel_utilisateur'])?></td>
                          <td><?=htmlspecialchars($user['is_active'])?></td>
                          <td><?=htmlspecialchars($user['is_suspended'])?></td>
                          <td><?=htmlspecialchars($user['motif_suspension'] ?? '')?></td>
                          <td><?=htmlspecialchars($user['role_utilisateur'] ?? '')?></td>
                          <td>
                                <!-- Bouton Edit pour ouvrir le modal -->
                                <button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                    data-id="<?= $user['id_utilisateur'] ?>"
                                    data-nom="<?= htmlspecialchars($user['nom_utilisateur']) ?>"
                                    data-email="<?= htmlspecialchars($user['courriel_utilisateur']) ?>"
                                    data-role="<?= htmlspecialchars($user['role_utilisateur']) ?>"
                                    data-suspended="<?= $user['is_suspended'] ?>"
                                    data-active="<?= $user['is_active'] ?>"
                                    data-motif="<?= htmlspecialchars($user['motif_suspension'] ?? '') ?>">
                                    <i class="bx bx-edit-alt me-1"></i> Modifier
                                </button>

                                <!-- Bouton Delete -->
                                <button type="button" class="btn btn-danger delete-btn"
                                    data-id="<?= $user['id_utilisateur'] ?>">
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
    <!-- Modal Modifier Utilisateur -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserLabel">Modifier l'utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="edit_id" name="id_utilisateur">
                    <input type="hidden" id="csrf_token" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <div class="mb-3">
                        <label for="edit_nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="edit_nom" name="nom_utilisateur" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="courriel_utilisateur" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Rôle</label>
                        <select class="form-control" id="edit_role" name="role_utilisateur">
                            <option value="admin">Admin</option>
                            <option value="user">Utilisateur</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">État</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="edit_active_yes" name="is_active" value="1">
                            <label class="form-check-label" for="edit_active_yes">Actif</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="edit_active_no" name="is_active" value="0">
                            <label class="form-check-label" for="edit_active_no">Inactif</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Suspendu</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="edit_suspended_yes" name="is_suspended" value="1">
                            <label class="form-check-label" for="edit_suspended_yes">Oui</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="edit_suspended_no" name="is_suspended" value="0">
                            <label class="form-check-label" for="edit_suspended_no">Non</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_motif" class="form-label">Motif Suspension</label>
                        <textarea class="form-control" id="edit_motif" name="motif_suspension"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
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
        // Récupérer les boutons Edit et mettre à jour les valeurs du modal
        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("edit_id").value = this.getAttribute("data-id");
                document.getElementById("edit_nom").value = this.getAttribute("data-nom");
                document.getElementById("edit_email").value = this.getAttribute("data-email");
                document.getElementById("edit_role").value = this.getAttribute("data-role");
                document.getElementById("edit_motif").value = this.getAttribute("data-motif");

                document.getElementById("edit_active_yes").checked = (this.getAttribute("data-active") == "1");
                document.getElementById("edit_active_no").checked = (this.getAttribute("data-active") == "0");
                document.getElementById("edit_suspended_yes").checked = (this.getAttribute("data-suspended") == "1");
                document.getElementById("edit_suspended_no").checked = (this.getAttribute("data-suspended") == "0");
            });
        });

        // Gestion de la soumission du formulaire Update
        document.getElementById("editUserForm").addEventListener("submit", function (user) {
            user.preventDefault();
            
            let formData = new FormData(this);
            formData.append("csrf_token", "<?= $_SESSION['csrf_token'] ?? '' ?>");
            
            fetch('/PHP2/api/users/update', {
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

        // Suppression d'un utilisateur
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                let userId = this.getAttribute("data-id");

                if (confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) {
                    fetch("/PHP2/api/users/delete", {
                        method: "POST",
                        body: JSON.stringify({ id_utilisateur: userId, csrf_token: "<?= $_SESSION['csrf_token'] ?>" }),
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
