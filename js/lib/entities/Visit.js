/**
 * An ORM mapping for the Visit entity.
 */
export class Visit {
    /**
     * Creates a new Visit from the given data.
     *
     * @param {number} id         The id of the visit.
     * @param {string} doctorName The name of the doctor.
     * @param {string} type       The type of visit.
     */
    constructor(id, doctorName, type) {
        this.id         = id;
        this.doctorName = doctorName;
        this.type       = type;
    }

    /**
     * Fetches all the visits that match the given filters
     * or an error in case of failure.
     *
     * @param {string} doctorName The name of the doctor.
     * @param {string} type       The type of visit.
     *
     * @returns {Promise<Visit[]|Error>}
     */
    static async findBy(doctorName = '', type = '') {
        const query = new URLSearchParams([
            ['doctor', doctorName],
            ['type', type],
        ]);

        const response = await fetch(`/api/visit/?${query}`);
        if (!response.ok) {
            const { error } = await response.json();
            return new Error(error);
        }

        const visits = await response.json();
        return visits.map(({ id, doctor, type }) => new Visit(id, doctor, type));
    }
}
