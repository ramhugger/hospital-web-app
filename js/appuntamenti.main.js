import { Appointment }                                    from '/js/lib/entities/Appointment.js';
import { debounce, getTimeFilterValue, getUnixTimestamp } from '/js/lib/utils.js';


(async () => {
    const clientNameFilter = document.getElementById('clientName');
    const doctorFilter     = document.getElementById('doctor');
    const visitType        = document.getElementById('visitType');
    const timeFilter       = document.getElementById('time');
    const tbody            = document.getElementsByTagName('tbody')[0];

    /**
     * Refreshes the given table body according to the given filters.
     *
     * @param {HTMLTableSectionElement} tbody The table body element.
     * @param {string} clientName             The name of the client.
     * @param {string} doctor                 The name of the doctor.
     * @param {string} visitType              The type of visit.
     * @param {number} time                   The time of the visit.
     *
     * @returns {Promise<void>}
     */
    async function refreshTable(tbody, clientName, doctor, visitType, time) {
        const appointments = await Appointment.findBy(clientName, doctor, visitType, time);
        if (appointments instanceof Error) {
            alert(`An error has occurred: ${appointments.message}`);
            return;
        }

        tbody.replaceChildren(...appointments.map(({ clientName, doctor, visitType, time }) => {
            const tr = document.createElement('tr');

            tr.appendChild(document.createElement('td')).textContent = clientName;
            tr.appendChild(document.createElement('td')).textContent = doctor;
            tr.appendChild(document.createElement('td')).textContent = visitType;
            tr.appendChild(document.createElement('td')).textContent = time;

            return tr;
        }));
    }

    clientNameFilter.addEventListener('keyup', debounce(async ({ target }) => {
        const time = getUnixTimestamp(getTimeFilterValue(timeFilter));
        await refreshTable(tbody, target.value, doctorFilter.value, visitType.value, time || '');
    }, 250));

    doctorFilter.addEventListener('keyup', debounce(async ({ target }) => {
        const time = getUnixTimestamp(getTimeFilterValue(timeFilter));
        await refreshTable(tbody, clientNameFilter.value, target.value, visitType.value, time || '');
    }, 250));

    visitType.addEventListener('keyup', debounce(async ({ target }) => {
        const time = getUnixTimestamp(getTimeFilterValue(timeFilter));
        await refreshTable(tbody, clientNameFilter.value, doctorFilter.value, target.value, time || '');
    }, 250));

    timeFilter.addEventListener('change', debounce(async ({ target }) => {
        const time = getUnixTimestamp(getTimeFilterValue(target));
        await refreshTable(tbody, clientNameFilter.value, doctorFilter.value, visitType.value, time || '');
    }, 250));

    await refreshTable(tbody, clientNameFilter.value, doctorFilter.value, visitType.value, getUnixTimestamp(getTimeFilterValue(timeFilter)) || '');
})();
