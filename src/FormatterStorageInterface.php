<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat;

use Budgegeria\IntlFormat\Formatter\FormatterInterface;

interface FormatterStorageInterface
{
    public function addFormatter(FormatterInterface $formatter): void;
}
