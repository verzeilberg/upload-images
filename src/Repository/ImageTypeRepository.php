<?php

namespace verzeilberg\UploadImagesBundle\Repository;

use verzeilberg\UploadImagesBundle\Entity\ImageType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;

/**
 * @method ImageType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageType[]    findAll()
 * @method ImageType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageType::class);
    }

    /**
     * Save product group to database
     * @param $imageType
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save($imageType)
    {
        $result = [];
        try {
            $this->getEntityManager()->persist($imageType);
            $this->getEntityManager()->flush($imageType);
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][$e->getMessage()];
        }
        return $result;
    }

    /**
     * Delete imageType from database
     * @param $imageType
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete($imageType)
    {
        $result = [];
        try {
            $this->getEntityManager()->remove($imageType);
            $this->getEntityManager()->flush();
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][] = $e->getMessage();
        }
        return $result;
    }
}
