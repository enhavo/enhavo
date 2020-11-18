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
        if ($request->query->has('view_id')) { // todo: add redirect to query string
            $response->setTargetUrl($response->getTargetUrl() . '?view_id=' . $request->query->get('view_id'));
        }

        return $response;
    }
}
