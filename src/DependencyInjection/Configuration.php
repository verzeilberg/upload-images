<?php


namespace verzeilberg\UploadImagesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('upload_images');
        $treeBuilder->getRootNode()
                ->children()
                    ->integerNode('version')->end()
                ->end();

        var_dump($treeBuilder); die;

        return $treeBuilder;
    }
}