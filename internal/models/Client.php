<?php

require_once __DIR__ . '/../sql/Database.php';

/**
 * An ORM mapping for the `hospital`.`client` table.
 *
 * @author Marco Mercandalli <marco.mercandalli@icloud.com>
 */
class Client
{
    ////////////////////////////////////////////////////////////////////////////
    /// Fields
    ////////////////////////////////////////////////////////////////////////////

    /** @var ?int Primary key */
    public $id;

    /** @var string Tax code */
    public $tax_code;

    /** @var string First name */
    public $first_name;

    /** @var string Last name */
    public $last_name;

    /** @var int Date of birth */
    public $date_of_birth;

    /** @var ?string Gender */
    public $gender;

    /**
     * Creates a new client with the given data.
     */
    public function __construct(
        ?int    $id,
        string  $tax_code,
        string  $first_name,
        string  $last_name,
        int     $date_of_birth,
        ?string $gender
    ) {
        $this->id = $id;
        $this->tax_code = $tax_code;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->date_of_birth = $date_of_birth;
        $this->gender = $gender;
    }

    private const FIND_BY_QUERY = '
        SELECT `id`, `tax_code`, `first_name`, `last_name`, UNIX_TIMESTAMP(`date_of_birth`) AS `date_of_birth`, `gender`
            FROM `hospital`.`client`
            WHERE (`tax_code` = :tax_code OR :tax_code IS NULL);
    ';

    /**
     * Returns the clients that match the given filters.
     *
     * @param ?string $tax_code The tax code of the client.
     *
     * @return array{self}
     */
    public static function findBy(?string $tax_code): array
    {
        // Validate the filters
        if (isset($tax_code)) {
            assert(strlen($tax_code) === 16, 'The tax code must be 16 characters long.');
        }

        $conn = Database::connect('hospital');

        $stmt = $conn->prepare(self::FIND_BY_QUERY);
        $stmt->bindValue(':tax_code', $tax_code);

        assert($stmt->execute(), $stmt->errorInfo()[2]);
        return array_map(function ($row) {
            return new self(...$row);
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private const INSERT_QUERY = '
        INSERT INTO `hospital`.`client` (`tax_code`, `first_name`, `last_name`, `date_of_birth`, `gender`)
        VALUES (:tax_code, :first_name, :last_name, :date_of_birth, :gender);
    ';

    /**
     * Inserts self as a new client in the database.
     *
     * @return bool True on success, false otherwise.
     */
    public function insert(): bool
    {
        $conn = Database::connect('hospital');

        $stmt = $conn->prepare(self::INSERT_QUERY);
        $stmt->bindValue(':tax_code', $this->tax_code);
        $stmt->bindValue(':first_name', $this->first_name);
        $stmt->bindValue(':last_name', $this->last_name);
        $stmt->bindValue(':date_of_birth', date('Y-m-d', $this->date_of_birth));
        $stmt->bindValue(':gender', $this->gender);

        return $stmt->execute() && $stmt->rowCount() === 1;
    }
}
