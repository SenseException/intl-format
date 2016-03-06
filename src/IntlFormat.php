<?php

namespace Budgegeria\IntlFormat;

class IntlFormat
{
    /**
     * @var string
     */
    private $pattern = '/(%[%a-z0-9_\$]+)/i';

    /**
     * @var array
     */
    private $formatter = [];

    /**
     * @param array $formatter
     */
    public function __construct(array $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @param string $message
     * @return string
     */
    public function format($message)
    {
        $parsedMessage = preg_split($this->pattern, $message, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);

        $formatValues = [];
        foreach ($parsedMessage as &$part) {
            if ($this->isKey($part) && $this->has($part)) {

            }
        }
        $message = implode('', $parsedMessage);

        return $message;
    }

    /**
     * @param string $key
     * @return bool
     */
    private function has($key)
    {
        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    private function isKey($key)
    {
        return strlen($key) > 1 && $key[0] === '%' && $key[1] === '%';
    }
}