<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-12
 * Time: 14:38
 */

namespace Enhavo\Bundle\AppBundle\Behat\Context;


use Behat\Behat\Context\Context;
use Symfony\Component\Filesystem\Filesystem;

class DebugContext implements ClientAwareContext, Context
{
    use ClientAwareTrait;

    /**
     * @Given save DOM as :name
     */
    public function saveDOM($name)
    {
        $html = $this->getClient()->getCrawler()->html();
        $file = sprintf('%s/%s.html', $this->getDomDir(), $name);
        $fs = new Filesystem();
        $fs->dumpFile($file, $html);
    }

    /**
     * @Given take a screenshot as :name
     */
    public function takeScreenshot($name)
    {
        $file = sprintf('%s/%s.png', $this->getScreenshotDir(), $name);
        $this->getClient()->takeScreenshot($file);
    }

    /**
     * @Given clear logs
     */
    public function clearLogs()
    {
        $file = sprintf('%s/%s.log', $this->getLogDir(), getenv('APP_ENV'));
        $fs = new Filesystem();
        if($fs->exists($file)) {
            $fs->remove($file);
        }
    }

    private function getDomDir()
    {
        return __DIR__ . '/../../../../../../var/behat/dom';
    }

    private function getLogDir()
    {
        return __DIR__ . '/../../../../../../var/logs';
    }

    private function getScreenshotDir()
    {
        return __DIR__ . '/../../../../../../var/behat/screenshot';
    }
}
