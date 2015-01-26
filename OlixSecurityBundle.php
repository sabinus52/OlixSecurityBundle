<?php

namespace Olix\SecurityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OlixSecurityBundle extends Bundle
{

    /**
     * @see \Symfony\Component\HttpKernel\Bundle\Bundle::getParent()
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }

}
