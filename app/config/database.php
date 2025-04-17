<?php
/**
 * Database Configuration
 * Handles database connection and queries
 */

// Database Credentials
define('DB_HOST', 'localhost');      // Database host
define('DB_USER', 'root');           // Database username
define('DB_PASS', '');               // Database password
define('DB_NAME', 'TourGuide');      // Database name - changed from tour_guide_db to TourGuide as requested

/**
 * Database Connection Class
 * Handles connection and provides methods for queries
 */
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    private $dbh;      // Database handler
    private $stmt;     // Statement
    private $error;    // Error message
    
    /**
     * Constructor - Establish database connection
     */
    public function __construct() {
        // Set DSN (Data Source Name)
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        
        // Set options
        $options = [
            PDO::ATTR_PERSISTENT => true,                // Persistent connection
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, // Return objects by default
            PDO::ATTR_EMULATE_PREPARES => false          // Use real prepared statements
        ];
        
        // Create PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo 'Database Connection Error: ' . $this->error;
        }
    }
    
    /**
     * Prepare statement with query
     * 
     * @param string $sql The SQL query
     * @return void
     */
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }
    
    /**
     * Bind values to prepared statement
     * 
     * @param string $param The parameter to bind
     * @param mixed $value The value to bind
     * @param mixed $type The data type (optional)
     * @return void
     */
    public function bind($param, $value, $type = null) {
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        
        $this->stmt->bindValue($param, $value, $type);
    }
    
    /**
     * Execute the prepared statement
     * 
     * @return boolean
     */
    public function execute() {
        return $this->stmt->execute();
    }
    
    /**
     * Get result set as array of objects
     * 
     * @return array
     */
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }
    
    /**
     * Get single record as object
     * 
     * @return object
     */
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }
    
    /**
     * Get row count
     * 
     * @return int
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    /**
     * Get last inserted ID
     * 
     * @return int
     */
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
    
    /**
     * Begin a transaction
     * 
     * @return boolean
     */
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }
    
    /**
     * Commit a transaction
     * 
     * @return boolean
     */
    public function commit() {
        return $this->dbh->commit();
    }
    
    /**
     * Rollback a transaction
     * 
     * @return boolean
     */
    public function rollBack() {
        return $this->dbh->rollBack();
    }
}
