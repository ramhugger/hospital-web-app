# API

This API is a REST API that uses JSON as its data format.
It aims to allow almost direct access to the data in the database.

## Endpoints

- `/api/appointment`:
    - `GET`: Returns a list of all appointments that match the given filters
        - `?client_name`: Case-sensitive name of the client
        - `?doctor`: Case-sensitive name of the doctor
        - `?visit_type`: Case-sensitive name of the visit type
        - `?time`: Date(time) of the appointment
    - `POST`: Creates a new appointment with the given data
        - `clientId`: Database ID of the client
        - `visitId`: Database ID of the visit
        - `time`: Date and time of the appointment
- `/api/client`:
    - `GET`: Returns a list of all clients that match the given filters
        - `?tax_code`: Case-insensitive tax code of the client
    - `POST`: Creates a new client with the given data
        - `taxCode`: Tax code of the client
        - `firstName`: First name of the client
        - `lastName`: Last name of the client
        - `dateOfBirth`: Date of birth of the client
        - `?gender`: (Optional) Gender of the client
- `/api/visit`:
    - `GET`: Returns a list of all visits that match the given filters
        - `?doctor`: Case-sensitive name of the doctor
        - `?type`: Case-sensitive name of the visit type

## Notes

- This API always responds with JSON-encoded data.
- This API always assumes that GET parameters are passed as query parameters in the request URI.
- This API always assumes that POST parameters are passed as JSON-encoded data in the request body.
- In case of error, a response with a status code other than 2xx|3xx is returned.
- In case of error, the response will be in the form of: _'{"error": "string"}'_
- In case of error on a production environment, the error message is replaced with the status text of the response. 
