<?php

require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Response.php';

/**
 * A basic REST controller heavily inspired by
 * the ASP.NET Core ControllerBase class.
 *
 * To use this class, you need to call the static methods
 * named after the HTTP method you want to handle
 * and provide them with some callback function
 * that will be called when the request is received.
 *
 * Callbacks are expected receive a Request as the only parameter
 * and to return a Response object.
 *
 * Initialization and tear down of the controller is automatically handled for you.
 *
 * @see    https://docs.microsoft.com/en-us/dotnet/api/microsoft.aspnetcore.mvc.controllerbase
 *
 * @author Marco Mercandalli <marco.mercandalli@icloud.com>
 */
class Controller
{
    /** @var array{string, callable} */
    private static $handlers = [];

    /**
     * Identifies the handler for the GET method.
     */
    public static function get(callable $handler): void
    {
        self::$handlers['GET'] = $handler;
    }

    /**
     * Identifies the handler for the POST method.
     */
    public static function post(callable $handler): void
    {
        self::$handlers['POST'] = $handler;
    }

    /**
     * Identifies the handler for the PUT method.
     */
    public static function put(callable $handler): void
    {
        self::$handlers['PUT'] = $handler;
    }

    /**
     * Identifies the handler for the DELETE method.
     */
    public static function delete(callable $handler): void
    {
        self::$handlers['DELETE'] = $handler;
    }

    /**
     * Initializes the controller.
     *
     * @throws \ErrorException
     */
    public static function __init(): void
    {
        if (defined('__INITIALIZED__')) {
            return;
        }
        define('__INITIALIZED__', true);

        // Automagically turn warnings into exceptions
        //
        // PS: PHP is extremely poor when it comes to its design (and we know it),
        // but this error handling shit must have been conceived
        // by the language designers while they were doing drugs in the basement.
        set_error_handler(function ($error_number, $error_string, $error_file, $error_line) {
            if (error_reporting() === 0) {
                return false;
            }

            throw new ErrorException($error_string, 0, $error_number, $error_file, $error_line);
        });

        // Handle the request and return the response
        //as the script execution comes to an end
        register_shutdown_function(function () {
            $request = new Request();
            $method = $request->getMethod();

            if (!array_key_exists($method, self::$handlers)) {
                $allow = implode(', ', array_keys(self::$handlers));
                $response = Response::json(405, ['error' => 'Method not allowed'], ['Allow' => $allow]);
            } else {
                $handler = self::$handlers[$method];

                try {
                    $response = $handler($request);
                } catch (Exception | Throwable $e) {
                    $is_dev = getenv('XDEBUG_TRIGGER') === 'DEBUG';
                    $error = $is_dev ? $e->getMessage() : 'Internal Server Error';

                    $response = Response::json(500, ['error' => $error]);
                }
            }

            $response->send();
            exit;
        });
    }
}

/** @noinspection PhpUnhandledExceptionInspection */
Controller::__init();
