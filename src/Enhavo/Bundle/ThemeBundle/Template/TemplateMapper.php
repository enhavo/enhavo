<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-07
 * Time: 11:46
 */

namespace Enhavo\Bundle\ThemeBundle\Template;

use Enhavo\Bundle\ThemeBundle\ThemeLoader\ThemeLoaderInterface;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateFilenameParser;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReference;
use Symfony\Component\Templating\TemplateReferenceInterface;

class TemplateMapper
{
    /**
     * @var ThemeLoaderInterface
     */
    private $loader;

    /**
     * @var TemplateNameParserInterface
     */
    private $parser;

    /**
     * TemplateMapper constructor.
     * @param ThemeLoaderInterface $loader
     * @param TemplateNameParserInterface $parser
     */
    public function __construct(ThemeLoaderInterface $loader, TemplateNameParserInterface $parser)
    {
        $this->loader = $loader;
        $this->parser = $parser;
    }

    public function map(TemplateReferenceInterface $template)
    {
        $theme = $this->loader->load();
        $mapping = $theme->getTemplate()->getMapping();

        foreach($mapping as $key => $map)
        {
            if($key === $template->getPath() || $key === $template->getLogicalName()) {
                return $this->parser->parse($map);
            }
        }

        return $template;
    }
}
