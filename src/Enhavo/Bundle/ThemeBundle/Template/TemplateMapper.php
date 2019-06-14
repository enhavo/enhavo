<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-07
 * Time: 11:46
 */

namespace Enhavo\Bundle\ThemeBundle\Template;

use Enhavo\Bundle\ThemeBundle\Theme\Loader\ThemeLoaderInterface;
use Enhavo\Bundle\ThemeBundle\Theme\ThemeManager;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;

class TemplateMapper
{
    /**
     * @var ThemeManager
     */
    private $themeManager;

    /**
     * @var TemplateNameParserInterface
     */
    private $parser;

    /**
     * TemplateMapper constructor.
     * @param ThemeManager $themeManager
     * @param TemplateNameParserInterface $parser
     */
    public function __construct(ThemeManager $themeManager, TemplateNameParserInterface $parser)
    {
        $this->themeManager = $themeManager;
        $this->parser = $parser;
    }

    public function map(TemplateReferenceInterface $template)
    {
        $theme = $this->themeManager->getTheme();
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
