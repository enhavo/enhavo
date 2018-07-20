<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.07.18
 * Time: 18:05
 */

namespace Enhavo\Bundle\NavigationBundle\Node\Voter;

class LinkHierarchyVoter extends LinkVoter
{
    protected function match($url)
    {
        $request = $this->getRequest();
        $path = sprintf('/%s', $request->getBasePath());
        $host = $request->getHost();

        $linkInfo = parse_url($url);

        if(isset($linkInfo['host']) && ($linkInfo['host'] != $host)) {
            return false;
        }

        if(isset($linkInfo['path'])) {

            $linkParts = explode('/', $linkInfo['path']);
            $pathParts = explode('/', $path);

            if(!array_diff($linkParts, $pathParts)) {
                return true;
            }
        }

        return false;
    }

    public function getType()
    {
        return 'link_hierarchy';
    }
}