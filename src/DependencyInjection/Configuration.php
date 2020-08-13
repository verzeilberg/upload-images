<?php


namespace verzeilberg\UploadImagesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('upload-images');

        $treeBuilder->getRootNode()
                ->children()
                    ->arrayNode('image')
                        ->children()
                            ->integerNode('save_originalll')->end()
                            ->scalarNode('crop_size')->end()
                        ->end()
                    ->end() // twitter
                ->end();
        return $treeBuilder;

    }
}