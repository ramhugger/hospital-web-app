/**
 * An ORM mapping for the Client entity.
 */
export class Client {
    /**
     * Creates a new client from the given data.
     *
     * @param {string} taxCode     The tax code of the client.
     * @param {string} firstName   The first name of the client.
     * @param {string} lastName    The last name of the client.
     * @param {number} dateOfBirth The UNIX timestamp of the date of birth of the client.
     * @param {string=} gender     The gender of the client expressed as a single optional character.
     */
    constructor(taxCode, firstName, lastName, dateOfBirth, gender) {
        this.taxCode     = taxCode;
        this.firstName   = firstName;
        this.lastName    = lastName;
        this.dateOfBirth = dateOfBirth;
        this.gender      = gender;
    }

    /**
     * Checks whether there is a client with the given tax code.
     *
     * @param {string} taxCode The tax code of the client.
     *
     * @returns {Promise<boolean|Error>}
     */
    static async exists(taxCode) {
        const query = new URLSearchParams([['tax_code', taxCode]]);

        const response = await fetch(`/api/client/?${query}`);
        if (!response.ok) {
            const { error } = await response.json();
            return new Error(error);
        }

        const clients = await response.json();
        return clients.length === 1;
    }

    /**
     * Inserts the client into the database
     * and returns an error in case of failure.
     *
     * @returns {Promise<Error|void>}
     */
    async insert() {
        const response = await fetch('/api/client/', {
            method : 'POST',
            headers: { 'Content-Type': 'application/json; charset=utf-8' },
            body   : JSON.stringify(this),
        });

        if (!response.ok) {
            const { error } = await response.json();
            return new Error(error);
        }
    }
}
