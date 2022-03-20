<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-04-04
 * Time: 16:30
 */

namespace Enhavo\Bundle\AppBundle\Translation;

use Symfony\Component\Translation\Loader\LoaderInterface;

class TranslationDumper
{
    /** @var LoaderInterface[] */
    private $loaders = array();

    /** @var array */
    private $translationFilesByLocale;

    /**
     * @param array $translationFilesByLocale all the translations whose index is the locale
     */
    public function __construct(array $translationFilesByLocale)
    {
        $this->translationFilesByLocale = $translationFilesByLocale;
    }

    /**
     * Add a translation loader if it does not exist.
     *
     * @param string $id The loader id.
     * @param LoaderInterface $loader A translation loader.
     */
    public function addLoader($id, $loader)
    {
        if (!array_key_exists($id, $this->loaders)) {
            $this->loaders[$id] = $loader;
        }
    }

    public function getTranslations($translationDomain, $locale)
    {
        $messages = [];
        $files = $this->getFiles($translationDomain, $locale);
        foreach($files as $file) {
            $messages = array_replace_recursive(
                $messages,
                $this->load($file, $locale, $translationDomain)
            );
        }
        return $messages;
    }

    private function getFiles($translationDomain, $locale)
    {
        $files = [];
        if (isset($this->translationFilesByLocale[$locale])) {
            foreach($this->translationFilesByLocale[$locale] as $file) {
                $info = pathinfo($file);
                $parts = explode('.', $info['filename']);
                $domain = array_shift($parts);
                if($domain == $translationDomain) {
                    $files[] = $file;
                }
            }

        }
        return $files;
    }

    private function load($file, $locale, $domain)
    {
        $extension = pathinfo($file)['extension'];
        if($extension == 'yml') {
            $extension = 'yaml';
        }
        $catalogue = $this->loaders[$extension]->load($file, $locale, $domain);
        return $catalogue->all($domain);
    }
}
