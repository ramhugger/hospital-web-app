import { Client }           from '/js/lib/entities/Client.js';
import { getUnixTimestamp } from '/js/lib/utils.js';


(async () => {
    const form = document.getElementsByTagName('form')[0];

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData      = new FormData(form);
        const entries       = Object.fromEntries(formData.entries());
        entries.dateOfBirth = getUnixTimestamp(new Date(entries.dateOfBirth));

        const { taxCode, firstName, lastName, dateOfBirth, gender } = entries;

        const client = new Client(taxCode, firstName, lastName, dateOfBirth, gender || null);
        const result = await client.insert();
        if (result instanceof Error) {
            alert(result.message);
        } else {
            alert('Ti sei registrato correttamente!');
            window.location.replace('/appuntamenti/prenota/');
        }
    });
})();
