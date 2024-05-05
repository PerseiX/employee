<?php

declare(strict_types=1);

namespace App\Payment\Domain\System;

use DateTimeInterface;

interface DateTimeProviderInterface
{
    public function getCurrentDate(): DateTimeInterface;
}
