<?php

require_once __DIR__ . '/../sql/Database.php';

/**
 * An ORM mapping for the `hospital`.`appointment` table.
 *
 * @author Marco Mercandalli <marco.mercandalli@icloud.com>
 */
class Appointment
{
    /** @var ?int Primary key */
    private $id;

    /** @var ?int Foreign key */
    private $tax_code;

    /** @var ?int Foreign key */
    private $visit_id;

    /** @var ?string Full client's name */
    public $client_name;

    /** @var ?string Full doctor's name */
    public $doctor;

    /** @var ?string The type of visit */
    public $visit_type;

    /** @var int The date and time of the appointment */
    public $time;

    /**
     * Creates a new raw appointment.
     *
     * @param ?int    $id       Primary key.
     * @param ?string $tax_code Client's tax code.
     * @param ?int    $visit_id Visit's primary key.
     * @param int     $time     The date and time of the appointment.
     */
    public function __construct(
        ?int    $id,
        ?string $tax_code,
        ?int    $visit_id,
        int     $time
    ) {
        $this->id = $id;
        $this->tax_code = $tax_code;
        $this->visit_id = $visit_id;
        $this->time = $time;
    }

    private const FIND_BY_QUERY = '
        SELECT `a`.`id`, CONCAT(`c`.`first_name`, \' \', `c`.`last_name`) `client_name`, `v`.`doctor`, `vt`.`visit_type`, UNIX_TIMESTAMP(`a`.`time`) AS `time`
            FROM `hospital`.`appointment` `a`
                INNER JOIN `hospital`.`client` `c` ON `a`.`client_id` = `c`.`id`
                INNER JOIN `hospital`.`visit` `v` ON `a`.`visit_id` = `v`.`id`
                INNER JOIN `hospital`.`visit_type` `vt` ON `v`.`visit_type_id` = `vt`.`id`
            WHERE (`doctor` LIKE :doctor OR :doctor IS NULL) AND
                  (`visit_type` LIKE :visit_type OR :visit_type IS NULL) AND
                  (DATE(`time`) = :time OR :time IS NULL)
            HAVING (`client_name` LIKE :client_name OR :client_name IS NULL);
    ';

    /**
     * Finds all the appointments that match the given filters.
     *
     * @param ?string $client_name The client's name.
     * @param ?string $doctor      The doctor's name.
     * @param ?string $visit_type  The type of visit.
     * @param ?int    $time        The date and time of the appointment.
     *
     * @return array{self}
     */
    public static function findBy(?string $client_name, ?string $doctor, ?string $visit_type, ?int $time): array
    {
        $conn = Database::connect('hospital');

        $stmt = $conn->prepare(self::FIND_BY_QUERY);
        $stmt->bindValue(':client_name', "%$client_name%");
        $stmt->bindValue(':doctor', "%$doctor%");
        $stmt->bindValue(':visit_type', "%$visit_type%");
        $stmt->bindValue(':time', isset($time) ? date('Y-m-d', $time) : null);

        assert($stmt->execute(), $stmt->errorInfo()[2]);
        return array_map(function ($row) {
            $new = new self($row['id'], null, null, $row['time']);
            $new->client_name = $row['client_name'];
            $new->doctor = $row['doctor'];
            $new->visit_type = $row['visit_type'];

            return $new;
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private const INSERT_QUERY = '
        INSERT INTO `hospital`.`appointment` (`client_id`, `visit_id`, `time`)
        SELECT `id`, :visit_id, :time
            FROM `hospital`.`client`
            WHERE `tax_code` = :tax_code;
    ';

    /**
     * Inserts self as a new appointment in the database.
     *
     * @return bool True on success, false otherwise.
     */
    public function insert(): bool
    {
        $conn = Database::connect('hospital');

        $stmt = $conn->prepare(self::INSERT_QUERY);
        $stmt->bindValue(':tax_code', $this->tax_code);
        $stmt->bindValue(':visit_id', $this->visit_id);
        $stmt->bindValue(':time', date('Y-m-d H:i:s', $this->time));

        return $stmt->execute() && $stmt->rowCount() === 1;
    }
}
