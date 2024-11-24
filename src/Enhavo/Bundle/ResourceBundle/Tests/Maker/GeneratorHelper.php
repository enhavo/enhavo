<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Maker;

use Symfony\Component\Filesystem\Filesystem;

class GeneratorHelper
{
    private readonly Filesystem $fs;

    public function __construct(
        private readonly string $targetDirectory
    )
    {
        $this->fs = new Filesystem();
    }

    public function generateFile(string $targetPath, string $templateName, array $variables = []): void
    {
        $basename = basename($targetPath);

        if (!$this->fs->exists($this->targetDirectory)) {
            $this->fs->mkdir($this->targetDirectory);
        }

        $file = $this->targetDirectory . '/' . $basename;
        $this->fs->dumpFile($file, $this->parseTemplate($templateName, $variables));
    }

    private function parseTemplate(string $templatePath, array $parameters): string
    {
        ob_start();
        extract($parameters, \EXTR_SKIP);
        include $templatePath;

        return ob_get_clean();
    }
}
