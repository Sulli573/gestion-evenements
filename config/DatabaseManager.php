<?php

class DatabaseManager
{
    private $host;
    private $port;
    private $dbname;
    private $user;
    private $password;
    private $pdo;

    /**
     * Constructor to initialize the database connection parameters.
     *
     * @param string $host Database host (e.g., localhost).
     * @param int $port Database port (e.g., 3306 for MySQL).
     * @param string $dbname Name of the database.
     * @param string $user Database username.
     * @param string $password Database password.
     */
    public function __construct($host, $port, $dbname, $user, $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Establishes a connection to the database using PDO.
     *
     * @return PDO The PDO instance.
     * @throws Exception If the connection fails.
     */
    public function connect()
    {
        if ($this->pdo === null) {
            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $this->host, $this->port, $this->dbname); //s pour une string , d pour un nombre

            try {
                $this->pdo = new PDO($dsn, $this->user, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new Exception('Connection failed: ' . $e->getMessage());
            }
        }

        return $this->pdo;
    }

    /**
     * Closes the PDO connection.
     */
    public function disconnect()
    {
        $this->pdo = null;
    }

    /**
     * Getter for the PDO instance (ensures a connection is established).
     *
     * @return PDO The PDO instance.
     */
    public function getPDO()
    {
        return $this->connect();
    }
}
/*
// Example usage
try {
    $manager = new PDOManager('localhost', 3306, 'entreprise', 'root', '');
    $pdo = $manager->getPDO();

    echo "Connected successfully";

    // Perform database operations here...

    $manager->disconnect();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
*/

/*
    // Requête pour récupérer les utilisateurs
    $query = "SELECT * FROM users";
    $stmt = $pdo->query($query);

    // Récupération des résultats
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //$user['name'] 
    
*/
/*
 
    //exemple prepare
    $query = "SELECT * FROM users WHERE email = :email AND name = :name";
    $stmt = $pdo->prepare($query);
        
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);

    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    */
    