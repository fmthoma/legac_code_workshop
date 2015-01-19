<?php

namespace TngWorkshop\BoardBundle\Service;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use TngWorkshop\BoardBundle\Entity\BoardMessage;
use TngWorkshop\BoardBundle\Entity\BoardMessageRepository;
use TngWorkshop\BoardBundle\Entity\BoardTag;
use TngWorkshop\BoardBundle\Entity\BoardTagRepository;

class BoardService
{
    /** @var EntityManager */
    private $entityManager;

    /** @var BoardMessageRepository */
    private $messageRepository;

    /** @var BoardTagRepository */
    private $tagsRepository;

    /** @var LoggerInterface */
    private $log;


    public function __construct(
        EntityManager $entityManager,
        BoardMessageRepository $messageRepository,
        BoardTagRepository $tagsRepository,
        LoggerInterface $log
    )
    {
        $this->log = $log;
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
        $this->tagsRepository = $tagsRepository;
    }

    /**
     * @param string $user
     * @param string $text
     * @param \DateTime $dateTime
     * @return BoardMessage
     */
    public function postMessage($user, $text, \DateTime $dateTime = null)
    {
        $dateTime = $dateTime ?: new \DateTime();
        $boardMessage = new BoardMessage();
        $boardMessage
            ->setUsername($user)
            ->setDate($dateTime)
            ->setMessageText($text);
        $this->entityManager->persist($boardMessage);
        $this->log->debug("Added $user's comment");
        return $boardMessage;
    }

    /**
     * @param int $page
     * @param int $entriesPerPage
     * @return BoardMessage[]
     */
    public function getMessages($page, $entriesPerPage)
    {
        return $this->messageRepository->findBy(
            array(),
            array('date' => 'DESC'),
            $entriesPerPage,
            ($page - 1) * $entriesPerPage
        );
    }

    /**
     * @param string $tag
     * @return BoardMessage[]
     */
    public function getMessagesWithTag($tag)
    {
        return $this->tagsRepository->get($tag)->getMessages()->toArray();
    }

    /**
     * @return string[]
     */
    public function getAllTags()
    {
        $tags = array();
        foreach ($this->tagsRepository->getAll() as $tagEntity) {
            $tags[] = $tagEntity->getTag();
        }
        return $tags;
    }

    /**
     * @param BoardMessage $boardMessage
     * @param string $tag
     */
    public function linkMessageToTag(BoardMessage $boardMessage, $tag)
    {
        $boardTag = $this->getOrCreateTag($tag);
        $boardMessage->addTag($boardTag);
    }

    private function getOrCreateTag($tag)
    {
        $tagEntity = $this->tagsRepository->get($tag);
        if ($tagEntity !== null) {
            return $tagEntity;
        }

        $tagEntity = new BoardTag();
        $tagEntity->setTag($tag);
        $this->entityManager->persist($tagEntity);
        return $tagEntity;
    }

    public function saveToDatabase()
    {
        $this->log->debug("Write to database");
        $this->entityManager->flush();
    }

    /**
     * @return int
     */
    public function countAllMessages()
    {
        return count($this->messageRepository->findAll());
    }
}