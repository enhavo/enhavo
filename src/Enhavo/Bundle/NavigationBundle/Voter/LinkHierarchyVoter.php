<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Voter;

class LinkHierarchyVoter extends LinkVoter
{
    protected function match($url)
    {
        $request = $this->requestStack->getCurrentRequest();
        $path = sprintf('/%s', $request->getBasePath());
        $host = $request->getHost();

        $linkInfo = parse_url($url);

        if (isset($linkInfo['host']) && ($linkInfo['host'] != $host)) {
            return false;
        }

        if (isset($linkInfo['path'])) {
            $linkParts = explode('/', $linkInfo['path']);
            $pathParts = explode('/', $path);

            if (!array_diff($linkParts, $pathParts)) {
                return true;
            }
        }

        return false;
    }
}
