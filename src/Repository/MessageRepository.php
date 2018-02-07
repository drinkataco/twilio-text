<?php
namespace App\Repository;

use App\Entity\Message;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Symfony\Bridge\Doctrine\RegistryInterface;

class MessageRepository extends ServiceEntityRepository
{
    /**
     * Default page size
     *
     * @var int
     */
    const PAGE_SIZE_DEFAULT = 100;

    /**
     * Selected Page Sise
     *
     * @var int
     */
    private $pageSize;

    /**
     * Total amount in dataset
     *
     * @var int
     */
    private $totalMessages;

    /**
     * Initiate Repository
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * Query Out Messages
     *
     * @param integer $pageNumber default page number
     * @param string  $orderBy    field to order by
     * @param string  $orderDir   order by direction (DESC or ASC)
     * @param integer $perPage
     *
     * @return array               Messages
     */
    public function getMessages(
        $pageNumber = 1,
        $orderBy = 'createdDate',
        $orderDir = 'DESC',
        $perPage = null
    ) {
        if (is_null($perPage)) {
            $this->pageSize = self::PAGE_SIZE_DEFAULT;
        }

        $query = $this->createQueryBuilder('m')
            ->orderBy('m.createdDate', 'DESC')
            ->getQuery();

        $pagedMessages = $this->paginate($query, $pageNumber, $this->pageSize);

        $this->totalMessages = $this->getMessageCount();

        return $pagedMessages;
    }

    /**
     * Get overall message count
     * @return [type] [description]
     */
    public function getMessageCount()
    {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Paginates query results
     *
     * @param Doctrine\ORM\Query $query
     * @param integer $page  Current Page
     * @param integer $limit Page Limit
     *
     * @return Paginator paginated results
     */
    private function paginate($query, $page, $limit)
    {
        $paginator = new Paginator($query);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @return int
     */
    public function getTotalMessages()
    {
        return $this->totalMessages;
    }
}
