<?php

namespace App\Template;

use App\Template\Annotation\MenuItem;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\AnnotationException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

// ToDo: implement caching
class MenuDiscovery
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * The Kernel root directory.
     *
     * @var string
     */
    private $rootDir;

    /**
     * @var array
     */
    private $menuItems = [];

    /**
     * MenuDiscovery constructor.
     *
     * @param $namespace
     *   The namespace of the menu items
     * @param $directory
     *   The directory of the menu items
     * @param $rootDir
     * @param Reader $annotationReader
     */
    public function __construct($namespace, $directory, $rootDir, Reader $annotationReader)
    {
        $this->namespace = $namespace;
        $this->annotationReader = $annotationReader;
        $this->directory = $directory;
        $this->rootDir = $rootDir;
    }

    /**
     * Returns all the menu items.
     */
    public function getMenuItems()
    {
        if (!$this->menuItems) {
            $this->discoverMenuItems();
        }

        return $this->menuItems;
    }

    /**
     * Discovers menu items.
     */
    private function discoverMenuItems()
    {
        $path = $this->rootDir.'/../src/'.$this->directory;
        $finder = new Finder();
        $finder->files()->name('*.php')->in($path);

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $namespace = $file->getRelativePath() ? '\\'.strtr($file->getRelativePath(), '/', '\\') : '';
            $class = $this->namespace.$namespace.'\\'.$file->getBasename('.php');
            $refl = new \ReflectionClass($class);

            foreach ($refl->getMethods() as $method) {
                $annotation = $this->annotationReader->getMethodAnnotation($method, 'App\Template\Annotation\MenuItem');
                if (!$annotation) {
                    continue;
                }

                if (null === $annotation->getPath()) {
                    $route = $this->annotationReader->getMethodAnnotation($method, 'Symfony\Component\Routing\Annotation\Route');
                    if (!$route) {
                        throw AnnotationException::semanticalError('An Symfony\Component\Routing\Annotation\Route annotation is required when using a App\Template\Annotation\MenuItem annotation');
                    }

                    $annotation->setPath($route->getName());
                }

                /* @var MenuItem $annotation */
                $this->menuItems[] = $annotation;
            }
        }
    }
}
