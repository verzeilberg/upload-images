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
                    ->integerNode('version')->defaultValue(100)->end()
                ->end();
        return $treeBuilder;
    }
}