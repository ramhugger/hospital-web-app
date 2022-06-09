/**
 * Debounce the given callback function by the given delay in milliseconds.
 *
 * @param {function} callback The callback function to debounce.
 * @param {number}   ms       The delay in milliseconds.
 *
 * @returns {(function(...[*]): void)|*}
 */
export function debounce(callback, ms) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => callback.apply(this, args), ms);
    };
}

/**
 * Computes the UNIX timestamp of the given date.
 *
 * @param {Date} date The date to convert.
 *
 * @returns {number|false}
 */
export function getUnixTimestamp(date) {
    const timestamp = parseInt((date.getTime() / 1000).toFixed(0));

    return isNaN(timestamp) ? false : timestamp;
}

/**
 * Returns the value of the given input element as a date.
 *
 * @param timeFilter The input element.
 *
 * @returns {Date}
 */
export function getTimeFilterValue(timeFilter) {
    return new Date(timeFilter.value);
}
