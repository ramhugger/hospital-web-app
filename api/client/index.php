<?php

require_once __DIR__ . '/../../internal/http/Controller.php';
require_once __DIR__ . '/../../internal/http/Request.php';
require_once __DIR__ . '/../../internal/http/Response.php';

require_once __DIR__ . '/../../internal/models/Client.php';

// Returns the filtered clients.
Controller::get(function (Request $req): Response {
    $tax_code = $req->getQueryParam('tax_code');
    if (isset($tax_code)) {
        $tax_code = strtoupper($tax_code);
    }

    $clients = Client::findBy($tax_code);
    return Response::json(200, $clients);
});

// Inserts a new client into the database.
Controller::post(function (Request $req): Response {
    @[
        'taxCode'     => $tax_code,
        'firstName'   => $first_name,
        'lastName'    => $last_name,
        'dateOfBirth' => $date_of_birth,
        'gender'      => $gender,
    ] = $req->getJsonBody();

    $client = new Client(null, $tax_code, $first_name, $last_name, $date_of_birth, $gender);
    return !$client->insert()
        ? Response::json(400, ['error' => 'Bad Request'])
        : Response::json(201, ['message' => 'Created']);
});
