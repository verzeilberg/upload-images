<?php


namespace verzeilberg\UploadImagesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="verzeilberg\UploadImagesBundle\Repository\ImageTypeRepository")
 * @ORM\Table(name="image_type",uniqueConstraints={@ORM\UniqueConstraint(name="search_idx", columns={"id"})})
 */
class ImageType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $folder;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true)
     */
    protected $width;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true)
     */
    protected $height;

    /**
     * @ORM\Column(name="is_crop", type="integer", length=1, nullable=false, options={"default"=0})
     */
    protected $isCrop = 0;

    /**
     * @ORM\Column(name="is_original", type="integer", length=1, nullable=false, options={"default"=0})
     */
    protected $isOriginal = 0;

    /**
     * Many imageTypes have one image. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Image", inversedBy="imageTypes")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    private $image;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param mixed $folder
     */
    public function setFolder($folder): void
    {
        $this->folder = $folder;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width): void
    {
        $this->width = $width;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height): void
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getIsCrop(): int
    {
        return $this->isCrop;
    }

    /**
     * @param int $isCrop
     */
    public function setIsCrop(int $isCrop): void
    {
        $this->isCrop = $isCrop;
    }

    /**
     * @return int
     */
    public function getIsOriginal(): int
    {
        return $this->isOriginal;
    }

    /**
     * @param int $isOriginal
     */
    public function setIsOriginal(int $isOriginal): void
    {
        $this->isOriginal = $isOriginal;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }




}