# upload-images
Symfony bundle to crop, rotate scal images

# Install

Add below code to *<b>config/packages/doctrine.yaml</b>* under the section mappings:

```doctrine
    orm:
        mappings:
            UploadImagesBundle:
                is_bundle: true
                type: annotation
                dir: 'Entity'
                prefix: 'Verzeilberg\UploadImagesBundle\Entity'
                alias: UploadImagesBundle```
