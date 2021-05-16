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
                ->arrayNode('mappings')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('upload_folder')->end()
                            ->scalarNode('max_file_size')->end()
                            ->arrayNode('allowed_file_types')
                                ->scalarPrototype()->end()
                            ->end()
                            ->arrayNode('image_types')
                                ->arrayPrototype()
                                        ->children()
                                            ->scalarNode('folder')->end()
                                            ->scalarNode('type_crop')->end()
                                            ->arrayNode('ratio')
                                                ->children()
                                                    ->integerNode('width')->end()
                                                    ->integerNode('height')->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}