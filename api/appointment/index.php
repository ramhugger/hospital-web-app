<?php

require_once __DIR__ . '/../../internal/http/Controller.php';
require_once __DIR__ . '/../../internal/http/Request.php';
require_once __DIR__ . '/../../internal/http/Response.php';

require_once __DIR__ . '/../../internal/models/Appointment.php';

// Returns all the filtered appointments.
Controller::get(function (Request $req): Response {
    $client_name = $req->getQueryParam('client_name');
    $doctor = $req->getQueryParam('doctor');
    $visit_type = $req->getQueryParam('visit_type');
    $time = $req->getQueryParam('time');
    $time = $time ? intval($time) : null;

    $appointments = Appointment::findBy($client_name, $doctor, $visit_type, $time);
    return Response::json(200, $appointments);
});

// Registers a new appointment.
Controller::post(function (Request $req): Response {
    @[
        'taxCode' => $tax_code,
        'visitId' => $visit_id,
        'time'    => $time,
    ] = $req->getJsonBody();

    $appointment = new Appointment(null, $tax_code, $visit_id, $time);
    return !$appointment->insert()
        ? Response::json(400, ['error' => 'Bad request'])
        : Response::json(201, ['message' => 'Created']);
});
