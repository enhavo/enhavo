<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-03
 * Time: 11:16
 */

namespace Enhavo\Bundle\NewsletterBundle\Newsletter;


use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Twig\Environment;

class NewsletterRenderer
{
    /** @var Environment */
    private $templateEnvironment;

    /** @var TemplateManager */
    private $templateManager;

    /** @var ParameterParserInterface */
    private $parameterParser;

    /** @var array */
    private $templates;

    /**
     * NewsletterRenderer constructor.
     *
     * @param Environment $templateEnvironment
     * @param TemplateManager $templateManager
     * @param ParameterParserInterface $parameterParser
     */
    public function __construct(
        Environment $templateEnvironment,
        TemplateManager $templateManager,
        ParameterParserInterface $parameterParser,
        array $templates
    ) {
        $this->templateEnvironment = $templateEnvironment;
        $this->templateManager = $templateManager;
        $this->parameterParser = $parameterParser;
        $this->templates = $templates;
    }

    public function render(Receiver $receiver): string
    {
        $template = $this->getTemplate($receiver->getNewsletter()->getTemplate());
        $content = $this->templateEnvironment->render($this->templateManager->getTemplate($template), [
            'resource' => $receiver->getNewsletter(),
            'receiver' => $receiver,
        ]);

        $content = $this->parameterParser->parse($content, $receiver->getParameters());

        return $content;
    }

    private function getTemplate(?string $key): string
    {
        if($key === null) {
            if(count($this->templates) === 1) {
                $key = array_keys($this->templates)[0];
                return $this->templates[$key]['template'];
            }
            throw new \Exception(sprintf('No template found for key "%s"', $key));
        }
        return $this->templates[$key]['template'];
    }
}
