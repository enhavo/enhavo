<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-02
 * Time: 14:05
 */

namespace Enhavo\Bundle\FormBundle\Form\Helper;

use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupCollectionInterface;

class EntrypointFileManager
{
    /**
     * @var EntrypointLookupCollectionInterface
     */
    private $entrypointLookup;

    /**
     * @var array
     */
    private $cache;

    public function __construct(EntrypointLookupCollectionInterface $entrypointLookup)
    {
        $this->entrypointLookup = $entrypointLookup;
    }

    public function getCssFiles($editorEntrypoint, $editorEntrypointBuild = null)
    {
        $key = sprintf('%s_%s', $editorEntrypoint, $editorEntrypointBuild);
        if(isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $this->cache[$key] = $this->entrypointLookup->getEntrypointLookup($editorEntrypointBuild)->getCssFiles($editorEntrypoint);
        return $this->cache[$key];
    }
}
