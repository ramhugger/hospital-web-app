<?php

/**
 * An abstraction over HTTP requests.
 *
 * @author Marco Mercandalli <marco.mercandalli@icloud.com>
 */
class Request
{
    /**
     * The HTTP method.
     *
     * @var string
     */
    private $method;

    /**
     * The HTTP headers.
     *
     * @var array{string, string}
     */
    private $headers;

    /**
     * The HTTP body.
     *
     * @var string
     */
    private $body;

    /**
     * Initializes the request.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->headers = getallheaders();
        $this->body = file_get_contents('php://input');
    }

    /**
     * Gets the HTTP method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Gets the named HTTP header value.
     *
     * @param string $header The header name.
     *
     * @return ?string
     */
    public function getHeader(string $header): ?string
    {
        return @$this->headers[$header];
    }

    /**
     * Gets the named query parameter.
     *
     * @param string $name
     *
     * @return ?string
     */
    public function getQueryParam(string $name): ?string
    {
        return self::hasQueryParam($name) ? $_GET[$name] : null;
    }

    /**
     * Checks whether the request has the named query parameter.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasQueryParam(string $name): bool
    {
        return array_key_exists($name, $_GET);
    }

    /**
     * Gets the HTTP body.
     *
     * @return string
     */
    public function getRawBody(): string
    {
        return $this->body;
    }

    /**
     * Gets the HTTP body as a decoded JSON object.
     *
     * @return array
     */
    public function getJsonBody(): array
    {
        if (empty($this->body) or strpos($this->getHeader('Content-Type'), 'application/json') !== 0) {
            return [];
        }

        return json_decode($this->body, true);
    }
}
