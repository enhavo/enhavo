<?php

namespace Enhavo\Bundle\MediaBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\MediaBundle\Checksum\ChecksumGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Content\PathContent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Helper\ProgressBar;

class MigrateCommand extends Command
{
    public function __construct(
        private string $storagePath,
        private ChecksumGeneratorInterface $checksumGenerator,
        private EntityManagerInterface $em,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:media:migrate')
            ->setDescription('Migrate to checksum storage')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->migrateFiles($output);
        $this->migrateFormat($output);

        return Command::SUCCESS;
    }

    private function migrateFiles(OutputInterface $output)
    {
        $fs = new Filesystem();

        $fileDir = $this->storagePath . '/file';

        if (!$fs->exists($fileDir)) {
            $fs->mkdir($fileDir);
        }

        $finder = new Finder();
        $total = $finder->files()->in($this->storagePath)->name('/^\d+$/')->depth('== 0')->count();
        if ($total > 0) {
            $output->writeln('Migrate files:');
            $progressBar = new ProgressBar($output, $total);
            $progressBar->start();
            $this->moveFromDir($this->storagePath, $fileDir,  $progressBar, false);
            $progressBar->finish();
        }
    }

    private function migrateFormat(OutputInterface $output)
    {
        $fs = new Filesystem();

        $formatDir = $this->storagePath . '/format';

        if (!$fs->exists($formatDir)) {
            $fs->mkdir($formatDir);
        }

        $total = 0;

        $finder = new Finder();
        foreach ($finder->directories()->in($this->storagePath)->depth('== 0') as $dir) {
            if (in_array(basename($dir), ['file', 'format'])) {
                continue;
            }

            $subDirFinder = new Finder();
            $total += $subDirFinder->files()->in($dir)->name('/^\d+$/')->depth('== 0')->count();
        }

        if ($total > 0) {
            $output->writeln('Migrate formats:');
            $progressBar = new ProgressBar($output, $total);
            $progressBar->start();

            $finder = new Finder();
            foreach ($finder->directories()->in($this->storagePath)->depth('== 0') as $dir) {
                if (in_array(basename($dir), ['file', 'format'])) {
                    continue;
                }

                $this->moveFromDir($dir, $formatDir, $progressBar, true);

                if ((new Finder())->files()->in($dir)->depth('== 0')->count() === 0) {
                    $fs->remove($dir);
                }
            }
            $progressBar->finish();
        }
    }

    private function moveFromDir(string $from, string $targetDir, ProgressBar $progressBar, bool $format)
    {
        $fs = new Filesystem();

        $finder = new Finder();
        foreach ($finder->files()->in($from)->name('/^\d+$/')->depth('== 0') as $file) {
            $id = basename($file);
            $checksum = $this->checksumGenerator->getChecksum(new PathContent($file));

            $prefix = substr($checksum, 0, 2);
            $newFilename = substr($checksum, 2);

            if ($format) {
                $this->em->getConnection()->executeQuery('UPDATE media_format SET checksum = ? WHERE file_id = ? AND name = ?', [
                    $checksum,
                    $id,
                    basename($from),
                ]);
            } else {
                $this->em->getConnection()->executeQuery('UPDATE media_file SET checksum = ? WHERE id = ?', [
                    $checksum,
                    $id,
                ]);
            }

            if (!$fs->exists($targetDir . '/' . $prefix)) {
                $fs->mkdir($targetDir . '/' . $prefix);
            }

            $target = $targetDir . '/' . $prefix . '/' . $newFilename;

            if ($fs->exists($target)) {
                $fs->remove($file);
            } else {
                $fs->rename($file, $target);
            }

            $progressBar->advance();
        }
    }
}
