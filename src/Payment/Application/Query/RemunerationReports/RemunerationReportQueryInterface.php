<?php

namespace App\Payment\Application\Query\RemunerationReports;

interface RemunerationReportQueryInterface
{
    public function execute(Filter $filter, Order $order);
}