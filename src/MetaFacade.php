<?php 

namespace Qdb\Meta;

use \Illuminate\Support\Facades\Facade as BaseFacade;

class MetaFacade extends BaseFacade
{

    protected static function getFacadeAccessor()
    {
        return 'meta';
    }
}
