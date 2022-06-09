import { Appointment }      from '/js/lib/entities/Appointment.js';
import { Client }           from '/js/lib/entities/Client.js';
import { Visit }            from '/js/lib/entities/Visit.js';
import { getUnixTimestamp } from '/js/lib/utils.js';


(async () => {
    const form         = document.getElementsByTagName('form')[0];
    const taxCodeInput = document.getElementById('taxCode');
    const visitInput   = document.getElementById('visitId');

    const visits = await Visit.findBy();
    if (visits instanceof Error) {
        alert(visits.message);
        return;
    }

    visitInput.replaceChildren(...visits.map(({ id, doctorName, type }) => {
        const option       = document.createElement('option');
        option.value       = id.toString();
        option.textContent = `${type} - ${doctorName}`;

        return option;
    }));

    taxCodeInput.addEventListener('change', async ({ target }) => {
        const exists = await Client.exists(target.value);
        if (exists instanceof Error) {
            alert(exists.message);
            return;
        }

        if (!exists) {
            const wantsToRegister = confirm('Non sei ancora registrato. Vuoi registrarti?');
            if (wantsToRegister) {
                window.location.replace('/registrati/');
            }
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        const taxCode = formData.get('taxCode');
        const visitId = parseInt(formData.get('visitId'));
        const time    = getUnixTimestamp(new Date(formData.get('time')));

        const appointment = new Appointment(taxCode, visitId, time, null, null, null);
        const result      = await appointment.insert();
        if (result instanceof Error) {
            alert(result.message);
        } else {
            alert('Appuntamento prenotato con successo');
            window.location.href = '/appuntamenti/';
        }
    });
})();
