<?php
require_once __DIR__ . '/../../config/DatabaseManager.php';
require_once __DIR__ . '/../../config/loadEnv.php';
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../controller/LoginController.php';
require_once __DIR__ . '/../../config/sessionManager.php';

loadEnv(__DIR__ . '/../../.env');

$database = new DatabaseManager(
    $_ENV['DB_HOST'],
    (int) $_ENV['DB_PORT'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

$userModel = new UserModel();
$loginController = new LoginController($userModel);

$message = ''; //stocker le message de retour

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $responseJson = $loginController->login($email, $password);
    $response = json_decode($responseJson, true);


    if ($response['status'] === 'Success') {
        if ($_SESSION['role_utilisateur'] === 'admin') {
            header("Location: /PHP2/views/template/admin/events.php");
            return;
        }
        header("Location: /PHP2/views/template/shows-events.php"); //redirection vers la page apres connexion reussie
        exit();
    } else {
        $message = $response['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css">

    <style>
        .vh-100 {
            height: 100vh;
        }

        .left-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
        }

        .h-custom-2 {
            height: 100%;
        }

        .right-section img {
            object-fit: cover;
            object-position: left;
            height: 100vh;
            width: 100%;
        }

        @media (max-width: 768px) {
            .right-section {
                display: none;
            }
        }
    </style>
</head>

<body>

    <section class="vh-100">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-6 left-section">
                    <div class="text-center mb-4">
                        <i class="fas fa-crow fa-2x me-2" style="color: #709085;"></i>
                    </div>

                    <form style="max-width: 400px; margin: auto;" method="POST" action="">
                        <h3 class="fw-normal mb-4 text-center">Se connecter</h3>

                        <!-- Message d'erreur -->
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-danger text-center">
                                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-outline mb-3">
                            <input type="email" id="email" name="email" class="form-control form-control-lg" required />
                            <label class="form-label" for="email">Courriel</label>
                        </div>

                        <div class="form-outline mb-3">
                            <input type="password" id="password" name="password" class="form-control form-control-lg" required />
                            <label class="form-label" for="password">Mot de passe</label>
                        </div>

                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

                        <div class="mb-4 text-center">
                            <button class="btn btn-info btn-lg w-100" type="submit">Connexion</button>
                        </div>

                        <p class="text-center">
                            Pas encore de compte? <a href="/PHP2/views/template/signup-template.php" class="link-info">Cliquez ici</a>
                        </p>
                    </form>
                </div>

                <div class="col-md-6 right-section d-none d-md-block">
                    <img src="../../assets/images/default.jpg"
                        alt="Login image">
                </div>

            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>

</body>

</html>