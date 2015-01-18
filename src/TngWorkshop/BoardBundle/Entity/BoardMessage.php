<?php

namespace TngWorkshop\BoardBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use TngWorkshop\BoardBundle\Entity\BoardTag;

/**
 * BoardMessage
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="TngWorkshop\BoardBundle\Entity\BoardMessageRepository")
 */
class BoardMessage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=255)
     */
    private $userName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $messageText;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="BoardTag", inversedBy="messages")
     * @ORM\JoinTable(
     *      name="tags_comments",
     *      joinColumns={@ORM\JoinColumn(name="commentId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tagId", referencedColumnName="id")}
     * )
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userName
     *
     * @param string $userName
     * @return BoardMessage
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string 
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return BoardMessage
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set messageText
     *
     * @param string $messageText
     * @return BoardMessage
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;

        return $this;
    }

    /**
     * Get messageText
     *
     * @return string
     */
    public function getMessageText()
    {
        return $this->messageText;
    }

    /**
     * Add tags
     *
     * @param BoardTag $tags
     * @return BoardMessage
     */
    public function addTag(BoardTag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param BoardTag $tags
     */
    public function removeTag(BoardTag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
