<?php

require_once __DIR__ . '/../../internal/http/Controller.php';
require_once __DIR__ . '/../../internal/http/Request.php';
require_once __DIR__ . '/../../internal/http/Response.php';

require_once __DIR__ . '/../../internal/models/Visit.php';

// Returns the filtered visits.
Controller::get(function (Request $req): Response {
    $doctor = $req->getQueryParam('doctor');
    $type = $req->getQueryParam('type');

    $visits = Visit::findBy($doctor, $type);
    return Response::json(200, $visits);
});
