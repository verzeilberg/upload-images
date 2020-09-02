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
                    ->stringNode('upload_folder')->defaultValue('%kernel.project_dir%/public/uploads/images')->info('Folder where the images will be uploaded')->end()
                    ->integerNode('max_file_size')->defaultValue(20000000)->info('Max file size in bytes')->end()
                ->end();
        return $treeBuilder;
    }
}