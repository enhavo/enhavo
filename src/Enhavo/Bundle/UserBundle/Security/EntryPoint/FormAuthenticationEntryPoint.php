<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Security\EntryPoint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\EntryPoint\FormAuthenticationEntryPoint as DecoratedEntryPoint;


/**
 * Class FormAuthenticationEntryPoint
 * @package Enhavo\Bundle\UserBundle\Security\EntryPoint
 */
class FormAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    /** @var DecoratedEntryPoint|null */
    private $entryPoint;

    /**
     * FormAuthenticationEntryPoint constructor.
     * @param DecoratedEntryPoint|null $entryPoint
     */
    public function __construct(?DecoratedEntryPoint $entryPoint)
    {
        $this->entryPoint = $entryPoint;
    }


    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $response = $this->entryPoint->start($request, $authException);
        $params = [];
        if ($request->query->has('view_id')) {
            $params['view_id'] = $request->query->get('view_id');
        }
        if ($request->hasSession() && $request->getSession()->has('enhavo.redirect_uri')) {
            $params['redirect'] = $request->getSession()->get('enhavo.redirect_uri');
            $request->getSession()->remove('enhavo.redirect_uri');
        }

        if (count($params)) {
            $response->setTargetUrl($response->getTargetUrl() .'?' .http_build_query($params));
        }

        return $response;
    }
}
