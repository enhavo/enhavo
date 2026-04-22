<?php

namespace Enhavo\Bundle\UserBundle\Documentation;

use Enhavo\Bundle\ApiBundle\Documentation\DescriberInterface;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Documentation;

class AuthorizationTokenDescriber implements DescriberInterface
{
    public function describe(Documentation $documentation, array $options = []): void
    {
        $documentation->components()
            ->securityScheme('BearerAuth')
                ->type('http')
                ->scheme('bearer')
                ->description('API token authentication using Bearer scheme')
        ;

        $documentation->security(['BearerAuth' => []]);
    }
}
