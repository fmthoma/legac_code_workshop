<?php

namespace TngWorkshop\BoardBundle\Service;

use Doctrine\ORM\EntityManager;
use mysqli;
use Psr\Log\LoggerInterface;
use TngWorkshop\BoardBundle\Entity\BoardMessageRepository;
use TngWorkshop\BoardBundle\Entity\BoardTagRepository;

class BoardService
{
    const HOST = "localhost";
    const USER = "board";
    const PASS = "board";
    const DBNAME = "tngworkshop";

    /** @var mysqli */
    private $db;

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
        $this->db = new mysqli(self::HOST, self::USER, self::PASS, self::DBNAME);
        $this->log = $log;
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
        $this->tagsRepository = $tagsRepository;
    }


    /**
     * @param string $user
     * @param string $text
     * @param \DateTime $dateTime
     * @return string message
     */
    public function postMessage($user, $text, \DateTime $dateTime = null)
    {
        $dateTime = $dateTime ?: new \DateTime();
        $date = $dateTime->getTimestamp();

        $message = $this->db->query('INSERT INTO comments (user, date, text) VALUES ' . "('$user', $date, '$text')")
            ? "Your entry has been added."
            : $this->db->error;

        $commentId = $this->db->insert_id;

        $m = array();
        preg_match_all('/#(\w*)/', $text, $m);
        if (!empty($m)) {
            foreach ($m[1] as $match) {
                $this->linkMessageToTag($commentId, $match);
            }
        }

        $this->log->debug($message);
        return $message;
    }

    /**
     * @param $page
     * @param int $entriesPerPage
     * @return array
     */
    public function getMessages($page, $entriesPerPage)
    {
        $start = ($page - 1) * $entriesPerPage;

        $result = $this->db->query('SELECT id, user, date, text from comments ORDER BY date DESC LIMIT ' . $start . ',' . $entriesPerPage);
        $entries = array();
        while (($entry = $result->fetch_assoc()) != null) {
            $entries[] = $entry;
        }
        return $entries;
    }

    /**
     * @param string $tag
     * @return array
     */
    public function getMessagesWithTag($tag)
    {
        $s_query = <<<S_QUERY
SELECT c.id, c.user, c.date, c.text FROM comments c RIGHT JOIN tags_comments t ON c.id = t.commentId LEFT JOIN tags tt ON tt.id = t.tagId WHERE tt.tag = '$tag';
S_QUERY;
        $result = $this->db->query($s_query);
        $entries = array();
        while (($entry = $result->fetch_assoc()) != null) {
            $entries[] = $entry;
        }
        return $entries;
    }

    /**
     * @return string[]
     */
    public function getAllTags()
    {
        $result = $this->db->query("SELECT tag FROM tags ORDER BY TAG ASC");
        $tags = array();
        while (($tag = $result->fetch_assoc())) {
            $tags[] = $tag['tag'];
        }
        return $tags;
    }

    /**
     * @param int $messageId
     * @param string $tag
     */
    public function linkMessageToTag($messageId, $tag)
    {
        $result = $this->db->query("SELECT id FROM tags WHERE tag = '$tag'");
        $data = is_object($result) ? $result->fetch_assoc() : array();
        if (!isset($data['id'])) {
            $this->db->query('INSERT INTO tags (tag) values (\'' . $tag . '\')');
            $tag_id = $this->db->insert_id;
        } else {
            $tag_id = $data[0][0];
        }
        $this->db->query("INSERT INTO tags_comments (tagId, commentId) VALUES ($tag_id, $messageId)");
    }

    /**
     * @return int
     */
    public function countAllMessages()
    {
        $result = $this->db->query('SELECT COUNT(*) as num_rows FROM comments')->fetch_assoc();
        return $result['num_rows'];
    }
}