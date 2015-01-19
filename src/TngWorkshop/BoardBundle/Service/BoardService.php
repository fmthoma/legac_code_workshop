<?php

namespace TngWorkshop\BoardBundle\Service;


class BoardService
{
    const DEFAULT_ENTRIES_PER_PAGE = 10;

    /**
     * @param string $user
     * @param string $text
     * @param \DateTime $dateTime
     * @return int insert_id
     */
    public function postMessage($user, $text, \DateTime $dateTime = null)
    {
    }

    /**
     * @param $startPage
     * @param int $entriesPerPage
     * @return array
     */
    public function getMessages($startPage, $entriesPerPage = self::DEFAULT_ENTRIES_PER_PAGE)
    {
    }

    /**
     * @param string $tag
     * @return array
     */
    public function getMessagesWithTag($tag)
    {
    }

    /**
     * @return string[]
     */
    public function getAllTags()
    {
    }

    /**
     * @param int $messageId
     * @param string $tag
     */
    public function linkMessageToTag($messageId, $tag)
    {
    }

    /**
     * @return int
     */
    public function countAllMessages()
    {
    }
}