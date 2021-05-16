<?php

namespace verzeilberg\UploadImagesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
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
     * Many images have Many imageTypes.
     * @ORM\ManyToMany(targetEntity="ImageType")
     * @ORM\JoinTable(name="image_imagetypes",
     *      joinColumns={@ORM\JoinColumn(name="id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="imageTypeId", referencedColumnName="id", onDelete="CASCADE", unique=true)}
     *      )
     */
    private $imageTypes;

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
     * @return ArrayCollection
     */
    public function getImageTypes(): ArrayCollection
    {
        return $this->imageTypes;
    }

    /**
     * @param ArrayCollection $imageTypes
     */
    public function setImageTypes(ArrayCollection $imageTypes): void
    {
        $this->imageTypes = $imageTypes;
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
}