<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\MessageParser;

use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use Override;

use function array_values;
use function assert;
use function is_array;
use function preg_grep;
use function preg_match;
use function preg_replace;
use function preg_split;

use const PREG_SPLIT_DELIM_CAPTURE;
use const PREG_SPLIT_NO_EMPTY;

class SprintfParser implements MessageParserInterface
{
    /**
     * @param mixed[] $values
     *
     * @throws InvalidTypeSpecifierException
     */
    #[Override]
    public function parseMessage(string $message, array $values): MessageMetaData
    {
        $parsedMessage = preg_split(
            '/(%[%]?(?# swapping:
             )(?:[\d]+\$)?(?# fraction padding:
             )[\d]*\.?\'?[\d#\-+]*(?# typespecifier:
             )[a-z\d_]*)/i',
            $message,
            -1,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE,
        );
        assert(is_array($parsedMessage));
        /** @var list<string> $typeSpecifiers */
        $typeSpecifiers = preg_grep(
            '/(^%(?# swapping:
             )(?:[\d]+\$)?(?# fraction padding:
             )\.?\'?[\d#\-+.]*(?# typespecifier:
             )[a-z\d_]+)/i',
            $parsedMessage,
        );
        assert(is_array($typeSpecifiers));

        // Change escaped % to regular %
        /** @var string[] $parsedMessage */
        $parsedMessage = preg_replace('/^%%/', '%', $parsedMessage);

        $values = $this->swapArguments($typeSpecifiers, $values);

        // Remove % and value position from type specifiers after argument swapping
        /** @var string[] $typeSpecifiers */
        $typeSpecifiers = preg_replace('/^%([0-9]+\$)?/', '', $typeSpecifiers);

        return new MessageMetaData($parsedMessage, $typeSpecifiers, $values);
    }

    /**
     * @param string[] $typeSpecifiers
     * @param mixed[]  $values
     *
     * @phpstan-return list<mixed>
     *
     * @throws InvalidTypeSpecifierException
     */
    private function swapArguments(array $typeSpecifiers, array $values): array
    {
        $swappedValues  = [];
        $typeSpecifiers = array_values($typeSpecifiers);

        foreach ($typeSpecifiers as $key => $typeSpecifier) {
            $matches = [];

            $index = $key;
            if (preg_match('/%([0-9]+)\$/', $typeSpecifier, $matches) === 1) {
                if ($matches[1] === '0') {
                    throw InvalidTypeSpecifierException::invalidTypeSpecifier($typeSpecifier);
                }

                $index = (int) $matches[1] - 1;
            }

            if (! isset($values[$index])) {
                throw InvalidTypeSpecifierException::unmatchedTypeSpecifier($typeSpecifier);
            }

            $swappedValues[] = $values[$index];
        }

        return $swappedValues;
    }
}
