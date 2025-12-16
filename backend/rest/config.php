<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

class Config {

    // Database Configuration
    private static $host = 'localhost';
    private static $dbName = 'iron_forge_gym';
    private static $username = 'milan';
    private static $password = 'tastatura123';
    private static $connection = null;

    public static function DB_HOST() {
        return self::$host;
    }
    public static function DB_NAME() {
        return self::$dbName;
    }
    public static function DB_USER() {
        return self::$username;
    }
    public static function DB_PASSWORD() {
        return self::$password;
    }

    // JWT SECRET KEY
    public static function JWT_SECRET() {
        return 'IRONFORGE_SUPER_SECRET_2025_!@#_JWT_KEY';
        // Možeš promijeniti kako želiš — samo neka bude dugačak string
    }

    public static function connect() {
        if(self::$connection === null) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbName,
                    self::$username,
                    self::$password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch(PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
