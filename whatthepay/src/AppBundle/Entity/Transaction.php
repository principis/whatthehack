<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransactionRepository")
 */
class Transaction
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
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="transactions")
     * @ORM\JoinColumn(name="account", referencedColumnName="id", onDelete="CASCADE")
     */
    private $account;


    /**
     * @ORM\ManyToOne(targetEntity="AccountPhoto")
     * @ORM\JoinColumn(name="account_photo", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $accountPhoto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="transactionDate", type="datetime")
     */
    private $transactionDate;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var bool
     * @ORM\Column(name="refused", type="boolean")
     */
    private $refused;

    public function __construct() {
        $this->refused = false;
    }

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
     * Set account
     *
     * @param Account $account
     *
     * @return Transaction
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set transactionDate
     *
     * @param \DateTime $transactionDate
     *
     * @return Transaction
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    /**
     * Get transactionDate
     *
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Transaction
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Transaction
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return AccountPhoto
     */
    public function getAccountPhoto()
    {
        return $this->accountPhoto;
    }

    /**
     * @param AccountPhoto $accountPhoto
     */
    public function setAccountPhoto($accountPhoto)
    {
        $this->accountPhoto = $accountPhoto;
    }

    /**
     * @return bool
     */
    public function isRefused()
    {
        return $this->refused;
    }

    /**
     * @param bool $refused
     */
    public function setRefused($refused)
    {
        $this->refused = $refused;
    }
}

