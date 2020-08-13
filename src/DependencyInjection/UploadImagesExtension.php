<?php


namespace verzeilberg\UploadImagesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class UploadImagesExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        var_dump($config);

        $definition = $container->getDefinition('upload.images.image');
        $definition->replaceArgument(0, $config['image']['save_original']);
        $definition->replaceArgument(1, $config['image']['crop_size']);
    }
}