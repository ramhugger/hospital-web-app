import { Visit }    from '/js/lib/entities/Visit.js';
import { debounce } from '/js/lib/utils.js';


(async () => {
    const doctorFilter = document.getElementById('doctor');
    const typeFilter   = document.getElementById('type');
    const tbody        = document.getElementsByTagName('tbody')[0];

    /**
     * Refreshes the given table body according to the given filters.
     *
     * @param {HTMLTableSectionElement} tbody The table body element.
     * @param {string} doctor                 The name of the doctor.
     * @param {string} type                   The type of visit.
     *
     * @returns {Promise<void>}
     */
    async function refreshTable(tbody, doctor, type) {
        const visits = await Visit.findBy(doctor, type);
        if (visits instanceof Error) {
            alert(`An error has occurred: ${visits.message}`);
            return;
        }

        tbody.replaceChildren(...visits.map(({ doctorName, type }) => {
            const tr = document.createElement('tr');

            tr.appendChild(document.createElement('td')).textContent = doctorName;
            tr.appendChild(document.createElement('td')).textContent = type;

            return tr;
        }));
    }

    doctorFilter.addEventListener('keyup', debounce(async ({ target }) => {
        await refreshTable(tbody, target.value, typeFilter.value);
    }, 250));

    typeFilter.addEventListener('keyup', debounce(async ({ target }) => {
        await refreshTable(tbody, doctorFilter.value, target.value);
    }, 250));

    await refreshTable(tbody, doctorFilter.value, typeFilter.value);
})();
