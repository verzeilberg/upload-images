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
                            ->scalarNode('upload_folder')
                                 ->defaultValue('%kernel.project_dir%/public/uploads/')
                            ->end()
                            ->scalarNode('max_file_size')
                                ->defaultValue(0)
                            ->end()
                            ->arrayNode('allowed_file_types')
                                ->scalarPrototype()
                                    ->defaultValue(['image/jpeg', 'image/png'])
                                ->end()
                            ->end()
                            ->arrayNode('image_types')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('folder')
                                            ->cannotBeEmpty()
                                        ->end()
                                        ->enumNode('type_crop')
                                            ->values(['manual', 'auto', 'none'])
                                        ->end()
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