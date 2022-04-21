<?php

namespace Bizbezzie\Zohobooks\Facades;

use Illuminate\Support\Facades\Facade;

class Item extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @method index()
     * @method store()
     * @method show()
     * @method update()
     * @method destroy()
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'tem';
    }
}
