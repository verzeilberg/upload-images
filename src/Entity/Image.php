<?php

namespace verzeilberg\UploadImagesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Serializable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="verzeilberg\UploadImagesBundle\Repository\ImageRepository")
 * @ORM\EntityListeners({"verzeilberg\UploadImagesBundle\EventListener\ImageListener"})
 * @ORM\Table(name="image",uniqueConstraints={@ORM\UniqueConstraint(name="search_idx", columns={"name_image"})})
 */
class Image implements Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;


    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    protected $nameImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $alt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $descriptionImage;

    /**
     * @ORM\Column(name="sort_order", type="integer", length=11, nullable=true);
     */
    protected $sortOrder = 0;

    /**
     * One Image has many imageTypes. This is the inverse side.
     * @ORM\OneToMany(targetEntity="ImageType", mappedBy="image", cascade={"persist", "remove"})
     */
    private Collection $imageTypes;

    public function __construct() {
        $this->imageTypes = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getNameImage()
    {
        return $this->nameImage;
    }

    /**
     * @param mixed $nameImage
     */
    public function setNameImage($nameImage): void
    {
        $this->nameImage = $nameImage;
    }

    /**
     * @return mixed
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param mixed $alt
     */
    public function setAlt($alt): void
    {
        $this->alt = $alt;
    }

    /**
     * @return mixed
     */
    public function getDescriptionImage()
    {
        return $this->descriptionImage;
    }

    /**
     * @param mixed $descriptionImage
     */
    public function setDescriptionImage($descriptionImage): void
    {
        $this->descriptionImage = $descriptionImage;
    }

    /**
     * @return int
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * @param int $sortOrder
     */
    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return Collection
     */
    public function getImageTypes(): Collection
    {
        return $this->imageTypes;
    }

    /**
     * @param Collection $imageTypes
     */
    public function setImageTypes(Collection $imageTypes): Collection
    {
        $this->imageTypes = $imageTypes;
    }

    /**
     * @param ImageType $imageType
     */
    public function addImageType(ImageType $imageType)
    {
        $this->imageTypes->add($imageType);
    }

    /**
     * @param ImageType $imageType
     */
    public function removeImageType(ImageType $imageType)
    {
        $this->imageTypes->remove($imageType);
    }

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param mixed $imageFile
     */
    public function setImageFile($imageFile): void
    {
        $this->imageFile = $imageFile;
    }


    public function serialize()
    {
        return serialize(array(
        $this->id,
        $this->nameImage,

    ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            ) = unserialize($serialized);
    }

    public function getImageLocationByType($type = 'original')
    {
        $imageType = $this->getImageByType($type);
        return $imageType->getFolder() . $this->getNameImage();
    }

    public function getImageByType($type = 'original')
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("type", $type));
        return $this->getImageTypes()->matching($criteria)->first();
    }
}