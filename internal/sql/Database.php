<?php

/**
 * A utility class for handling database connections.
 *
 * The credentials used to establish a database connection are stored
 * as private constants inside this class' definition.
 * You can change the credentials by editing those private constants.
 *
 * To establish a database connection, use the static method `connect()`
 * by passing the name of the database you want to connect to.
 *
 * @author Marco Mercandalli <marco.mercandalli@icloud.com>.
 */
class Database
{
    ////////////////////////////////////////////////////////////////////////////
    /// Connection credentials.
    ////////////////////////////////////////////////////////////////////////////

    private const ADDRESS  = 'localhost';
    private const USERNAME = 'root';
    private const PASSWORD = '';

    /**
     * Connects to the named database through the PDO interface.
     *
     * @param string $database Non-empty database name.
     *
     * @return \PDO
     */
    public static function connect(string $database): PDO
    {
        if (!$database) {
            throw new InvalidArgumentException('The database name cannot be empty.');
        }

        return new PDO(
            'mysql:host=' . self::ADDRESS . ';dbname=' . $database,
            self::USERNAME,
            self::PASSWORD,
            [
                PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
            ]
        );
    }
}
