<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\System;

use App\Payment\Domain\System\DateTimeProviderInterface;
use DateTime;
use DateTimeInterface;

final class DefaultDataTimeProviderInterface implements DateTimeProviderInterface
{
    public function getCurrentDate(): DateTimeInterface
    {
        return new DateTime();
    }
}
