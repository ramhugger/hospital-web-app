<?php

/**
 * An abstraction over HTTP responses.
 *
 * @author Marco Mercandalli <marco.mercandalli@icloud.com>
 */
class Response
{
    /**
     * The HTTP status code.
     *
     * @var int
     */
    private $statusCode;

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
     * Initializes the response.
     *
     * @param int                   $statusCode The HTTP status code.
     * @param string                $body       The HTTP body.
     * @param array{string, string} $headers    The HTTP headers.
     */
    public function __construct(int $statusCode, string $body, array $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @param int                   $statusCode The HTTP status code.
     * @param array                 $body       The body as an associative array.
     * @param array{string, string} $headers    The HTTP headers.
     *
     * @return self
     */
    public static function json(int $statusCode, array $body, array $headers = []): self
    {
        /**
         * Returns a camelCase version of the given string.
         *
         * @param string $str String to make camel-case.
         *
         * @return string
         */
        function snake_to_camel_case(string $str): string
        {
            return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $str))));
        }

        /**
         * Makes the keys of the given array camelCase.
         *
         * @param array $array Array to convert.
         *
         * @return void
         */
        function make_keys_camel_case(array &$array): void
        {
            foreach ($array as $key => &$value) {
                if (is_object($value)) {
                    $value = get_object_vars($value);
                    make_keys_camel_case($value);
                } else if (is_array($value)) {
                    make_keys_camel_case($value);
                    continue;
                }

                if (strpos($key, '_') !== false) {
                    $array[snake_to_camel_case($key)] = $value;
                    unset($array[$key]);
                }
            }
        }

        $headers['Content-Type'] = 'application/json; charset=utf-8';
        make_keys_camel_case($body);

        return new self($statusCode, json_encode($body), $headers);
    }

    /**
     * Sends the response back to the client.
     */
    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->body;
    }
}
