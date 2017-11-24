<?php
declare(strict_types = 1);

namespace Budgegeria\IntlFormat\MessageParser;

/**
 * The result of a parsed message.
 *
 * @internal
 */
class MessageMetaData
{
    /**
     * The parsed message.
     *
     * Each element in this array contains either plain text or the type specifier in its pure specified format defined
     * by the MessageParser. Each element has an unique integer key that is referenced by $typeSpecifiers.
     *
     * @var array
     */
    public $parsedMessage = [];

    /**
     * The list of type specifiers of the parsed message.
     *
     * This array only contains normalized type specifiers that are used in the Formatter classes. The key is an integer
     * value, that has the same value as its position in the $parsedMessage array.
     *
     * @var array
     */
    public $typeSpecifiers = [];

    /**
     * The list of values.
     *
     * This list contains the values, that need to be formatted by the Formatter classes. The values are already swapped
     * and in the same order as the $typeSpecifiers representing it.
     *
     * @var array
     */
    public $values = [];

    /**
     * @param array $parsedMessage
     * @param array $typeSpecifiers
     * @param array $values
     */
    public function __construct(array $parsedMessage, array $typeSpecifiers, array $values)
    {
        $this->parsedMessage = $parsedMessage;
        $this->typeSpecifiers = $typeSpecifiers;
        $this->values = $values;
    }
}