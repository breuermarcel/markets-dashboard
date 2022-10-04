<?php

namespace Breuermarcel\FinanceDashboard;

use Illuminate\Support\Facades\Facade;

class FinanceDashboardFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'finance-dashboard';
    }
}
