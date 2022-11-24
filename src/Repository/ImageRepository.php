<?php

namespace verzeilberg\UploadImagesBundle\Repository;

use verzeilberg\UploadImagesBundle\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;

/**
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    /**
     * Save image to database
     * @param $image
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save($image)
    {
        $result = [];
        try {
            $this->getEntityManager()->persist($image);
            $this->getEntityManager()->flush($image);
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][$e->getMessage()];
        }
        return $result;
    }

    /**
     * Delete image from database
     * @param $image
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete($image)
    {
        $result = [];
        try {
            $this->getEntityManager()->remove($image);
            $this->getEntityManager()->flush($image);
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][] = $e->getMessage();
        }
        return $result;
    }
}
