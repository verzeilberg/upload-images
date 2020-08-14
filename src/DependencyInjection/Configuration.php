<?php


namespace verzeilberg\UploadImagesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('upload_images');
        $rootNode->children()
                    ->arrayNode('image')
                        ->children()
                            ->integerNode('save_original')->end()
                            ->scalarNode('crop_size')->end()
                        ->end()
                    ->end()
                ->end();
        return $treeBuilder;

    }
}