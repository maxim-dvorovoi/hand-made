<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comments
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentsRepository")
 */
class Comments
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="commentary", type="text")
     */
    private $commentary;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", fetch = "EAGER")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     */
    private $product;

    /**
     * @var int
     *
     * @ORM\Column(name="likes", type="integer")
     */
    private $likes;

    /**
     * @var int
     *
     * @ORM\Column(name="dislikes", type="integer")
     */
    private $dislikes;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set commentary
     *
     * @param string $commentary
     *
     * @return Comments
     */
    public function setCommentary($commentary)
    {
        $this->commentary = $commentary;

        return $this;
    }

    /**
     * Get commentary
     *
     * @return string
     */
    public function getCommentary()
    {
        return $this->commentary;
    }

    /**
     * Set likes
     *
     * @param integer $likes
     *
     * @return Comments
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Get likes
     *
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set dislikes
     *
     * @param integer $dislikes
     *
     * @return Comments
     */
    public function setDislikes($dislikes)
    {
        $this->dislikes = $dislikes;

        return $this;
    }

    /**
     * Get dislikes
     *
     * @return int
     */
    public function getDislikes()
    {
        return $this->dislikes;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return Comments
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set product
     *
     * @param string $product
     *
     * @return Comments
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }
}

