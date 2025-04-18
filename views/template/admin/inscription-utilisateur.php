<?php
require_once __DIR__ . '/../../config/DatabaseManager.php';
require_once __DIR__ . '/../../config/loadEnv.php';
require_once __DIR__ . '/../../models/InscrireModel.php';
require_once __DIR__ . '/../../controller/InscrireController.php';
require_once __DIR__ . '/../../config/sessionManager.php';
require_once __DIR__ . '/../partial/navbar.php';

loadEnv(__DIR__ . '/../../.env');

$database = new DatabaseManager(
    $_ENV['DB_HOST'],
    (int) $_ENV['DB_PORT'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

$apiUrl="http://localhost/PHP2/api/inscrire";
$response=file_get_contents($apiUrl);
$inscription = json_decode($response, true);

if(!isset($inscription['inscription']) || empty($inscription['inscription'])) {
  #Au lieu d'afficher les erreurs on affichera un tableau vide
  $inscription['inscription'] = [];
}
?>

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

    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../assets/js/config.js"></script>
  </head>

  <body>
     <?php //require_once __DIR__ . '..partial/navbar.php';?>  
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
                <h5 class="card-header">Tickets</h5>
              </div>

                <div class="table-responsive text-start">
                  <table class="table">
                    <thead class="table-light">
                      <tr>      
                        <th>Numéro d'inscrption</th>                 
                        <th>Nom de l'événement</th>
                        <th>Date de l'événement</th>
                        <th>Heure de début l'événement</th>
                        <th>Nombre de personne</th>
                        <th>actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--Contenu des colones va créer une ligne par événement avec valeurs dans td-->
                      <?php foreach($inscription['inscription'] as $ins) :?> 
                        <tr>
                          <td><?=htmlspecialchars($ins['code'])?></td>
                          <td><?=htmlspecialchars($ins['nom_evenement'])?></td>
                          <td><?=htmlspecialchars($ins['heure_debut'])?></td>
                          <td><?=htmlspecialchars($ins['email_organisateur'])?></td>
                          <td><?=htmlspecialchars($ins['nbr_ticket'])?></td>
                          <td>

                            <!-- Bouton Delete -->
                            <button type="button" class="btn btn-danger delete-btn"
                                data-id="<?= $ins['code'] ?>">
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