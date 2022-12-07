<?php


namespace verzeilberg\UploadImagesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class UploadImagesExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $imageSettings = [];
        foreach ($config["mappings"] as $index => $mapping) {
            $imageSettings[$index]['upload_folder'] = $mapping['upload_folder'];
            $imageSettings[$index]['max_file_size'] = $mapping['max_file_size'];
            $imageSettings[$index]['allowed_file_types'] = $mapping['allowed_file_types'];
            foreach  ($mapping["image_types"] as $imageTypeIndex => $imageType)
            {
                $imageSettings[$index]['image_types'][$imageTypeIndex]['folder'] = $imageType['folder'];
                $imageSettings[$index]['image_types'][$imageTypeIndex]['type_crop'] = $imageType['type_crop'];

                if(isset($imageType['ratio'])) {
                    $imageSettings[$index]['image_types'][$imageTypeIndex]['ratio']['width'] = $imageType['ratio']["width"];
                    $imageSettings[$index]['image_types'][$imageTypeIndex]['ratio']['height'] = $imageType['ratio']["height"];
                }
            }
        }

        $container->setParameter('upload_images',  $imageSettings);
        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources'));
        $loader->load('services.yaml');

    }
}