<?php

namespace Breuermarcel\FinanceDashboard;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Breuermarcel\FinanceDashboard\Skeleton\SkeletonClass
 */
class FinanceDashboardFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'finance-dashboard';
    }
}
