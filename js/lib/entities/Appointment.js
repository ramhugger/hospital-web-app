/**
 * An ORM mapping for the Appointment entity.
 */
export class Appointment {
    #taxCode;

    #visitId;

    /**
     * Returns the date and time of the appointment
     * formatted according to the user's locale.
     */
    get time() {
        return new Date(this._time).toLocaleString();
    }

    /**
     * Creates a new Appointment from the given data.
     *
     * @param {string|null} taxCode    The client's ID.
     * @param {number|null} visitId    The visit's ID.
     * @param {number}      time       The UNIX timestamp of the appointment.
     * @param {string}      clientName The client's name.
     * @param {string}      doctor     The doctor's name.
     * @param {string}      visitType  The visit's type.
     */
    constructor(taxCode, visitId, time, clientName = '', doctor = '', visitType = '') {
        this.#taxCode = taxCode;
        this.#visitId = visitId;

        this.clientName = clientName;
        this.doctor     = doctor;
        this.visitType  = visitType;
        this._time      = time;
    }

    /**
     * Fetches all the appointments that match the given filters
     * or an error in case of failure.
     *
     * @param {string} clientName The client's name.
     * @param {string} doctor     The doctor's name.
     * @param {string} visitType  The visit's type.
     * @param {number} time       The UNIX timestamp date of the appointment.
     *
     * @returns {Promise<Appointment[]|Error>}
     */
    static async findBy(clientName = '', doctor = '', visitType = '', time = '') {
        const query = new URLSearchParams([
            ['client_name', clientName],
            ['doctor', doctor],
            ['visit_type', visitType],
            ['time', time],
        ]);

        const response = await fetch(`/api/appointment/?${query}`);
        if (!response.ok) {
            const { error } = await response.json();
            return new Error(`An error has occurred: ${error}`);
        }

        const appointments = await response.json();
        return appointments.map(({ time, clientName, doctor, visitType }) => {
            return new Appointment(null, null, time * 1000, clientName, doctor, visitType);
        });
    }

    /**
     * Inserts this appointment into the database
     * and returns an error in case of failure.
     *
     * @returns {Promise<Error|void>}
     */
    async insert() {
        const { _time: time } = this;
        const taxCode         = this.#taxCode;
        const visitId         = this.#visitId;

        console.assert(taxCode, 'No client ID was given');
        console.assert(visitId, 'No visit ID was given');
        console.assert(time !== undefined, 'No time was given');

        const response = await fetch('/api/appointment/', {
            method : 'POST',
            headers: { 'Content-Type': 'application/json; charset=utf-8' },
            body   : JSON.stringify({ taxCode, visitId, time }),
        });

        if (!response.ok) {
            const { error } = await response.json();
            return new Error(`An error has occurred: ${error}`);
        }
    }
}
