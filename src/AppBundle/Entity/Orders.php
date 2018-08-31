<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrdersRepository")
 */
class Orders
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
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="orderitems", type="string", length=255)
     */
    private $orderitems;

    /**
     * @var int
     *
     * @ORM\Column(name="payment", type="integer")
     */
    private $payment;

    /**
     * @var int
     *
     * @ORM\Column(name="delivery", type="integer")
     */
    private $delivery;

    /**
     * @var int
     *
     * @ORM\Column(name="totalprice", type="integer")
     */
    private $totalprice;


    /**
     * @var string
     *
     * @ORM\Column(name="suggestion", type="text", nullable=true)
     */
    private $suggestion;


    /**
     * @var int
     *
     * @ORM\Column(name="active", type="integer")
     */
    private $active;


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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Orders
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Orders
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Orders
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Orders
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set orderitems
     *
     * @param string $orderitems
     *
     * @return Orders
     */
    public function setOrderitems($orderitems)
    {
        $this->orderitems = $orderitems;

        return $this;
    }

    /**
     * Get orderitems
     *
     * @return string
     */
    public function getOrderitems()
    {
        return $this->orderitems;
    }

    /**
     * Set payment
     *
     * @param integer $payment
     *
     * @return Orders
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return int
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set delivery
     *
     * @param integer $delivery
     *
     * @return Orders
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * Get delivery
     *
     * @return int
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * Set totalprice
     *
     * @param integer $totalprice
     *
     * @return Orders
     */
    public function setTotalprice($totalprice)
    {
        $this->totalprice = $totalprice;

        return $this;
    }

    /**
     * Get totalprice
     *
     * @return int
     */
    public function getTotalprice()
    {
        return $this->totalprice;
    }

    /**
     * Set active
     *
     * @param integer $active
     *
     * @return Orders
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Set suggestion
     *
     * @param string $suggestion
     *
     * @return Orders
     */
    public function setSuggestion($suggestion)
    {
        $this->suggestion = $suggestion;

        return $this;
    }

    /**
     * Get suggestion
     *
     * @return string
     */
    public function getSuggestion()
    {
        return $this->suggestion;
    }

    /**
     * Get active
     *
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }
}

