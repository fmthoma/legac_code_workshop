<?php

namespace TngWorkshop\BoardBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use TngWorkshop\BoardBundle\Entity\BoardMessage;

/**
 * BoardTag
 *
 * @ORM\Table(name="tags")
 * @ORM\Entity(repositoryClass="TngWorkshop\BoardBundle\Entity\BoardTagRepository")
 */
class BoardTag
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
     * @ORM\Column(name="tag", type="string", length=255)
     */
    private $tag;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="BoardMessage", mappedBy="tags")
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
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
     * Set tag
     *
     * @param string $tag
     * @return BoardTag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string 
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Add messages
     *
     * @param BoardMessage $messages
     * @return BoardTag
     */
    public function addMessage(BoardMessage $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param BoardMessage $messages
     */
    public function removeMessage(BoardMessage $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
