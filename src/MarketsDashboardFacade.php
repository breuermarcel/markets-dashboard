<?php

namespace Breuermarcel\MarketsDashboard;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

class MarketsDashboardFacade extends IlluminateFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'markets-dashboard';
    }
}
