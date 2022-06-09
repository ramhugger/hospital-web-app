<?php

require_once __DIR__ . '/../sql/Database.php';

/**
 * An ORM mapping for the `hospital`.`visit` table.
 *
 * @author Marco Mercandalli <marco.mercandalli@icloud.com>
 */
class Visit
{
    ////////////////////////////////////////////////////////////////////////////
    /// Fields
    ////////////////////////////////////////////////////////////////////////////

    /** @var int Primary key */
    public $id;

    /** @var string Doctor's name */
    public $doctor;

    /** @var string Visit type */
    public $type;

    private const FIND_BY_QUERY = '
        SELECT `v`.`id`, `v`.`doctor`, `vt`.`visit_type` `type`
            FROM `hospital`.`visit` `v`
                INNER JOIN `hospital`.`visit_type` `vt` ON `vt`.`id` = `v`.`visit_type_id`
            WHERE (`v`.`doctor` LIKE :doctor OR :doctor IS NULL) AND
                  (`vt`.`visit_type` LIKE :type OR :type IS NULL);
    ';

    /**
     * Finds all the visits that match the given filters.
     *
     * @param ?string $doctor Doctor's name.
     * @param ?string $type   Visit type.
     *
     * @return array{self}
     */
    public static function findBy(?string $doctor, ?string $type): array
    {
        $conn = Database::connect('hospital');

        $stmt = $conn->prepare(self::FIND_BY_QUERY);
        $stmt->bindValue(':doctor', "%$doctor%");
        $stmt->bindValue(':type', "%$type%");

        assert($stmt->execute(), $stmt->errorInfo()[2]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }
}
