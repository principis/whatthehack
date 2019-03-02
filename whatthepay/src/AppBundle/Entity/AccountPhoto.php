<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountPhoto
 *
 * @ORM\Table(name="account_photo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccountPhotoRepository")
 */
class AccountPhoto
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
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="accountPhotos")
     * @ORM\JoinColumn(name="account", referencedColumnName="id", onDelete="CASCADE")
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="photoHash", type="text", nullable=true)
     */
    private $photoHash;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var bool
     *
     * @ORM\Column(name="isChild", type="boolean")
     */
    private $isChild;

    /**
     * @var bool
     *
     * @ORM\Column(name="isRegistered", type="boolean")
     */
    private $isRegistered;

    /**
     * @var int
     *
     * @ORM\Column(name="amountLimit", type="integer")
     */
    private $amountLimit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="disabledTo", type="date", nullable=true)
     */
    private $disabledTo;

    /**
     * @var bool
     *
     * @ORM\Column(name="disabled", type="boolean")
     */
    private $disabled;

    public function __construct() {
        $this->isRegistered = false;
        $this->isChild = false;
        $this->disabled = false;
    }

    public function __toString()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
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
     * @return AccountPhoto
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
     * Set PhotoHash
     *
     * @param string $PhotoHash
     *
     * @return AccountPhoto
     */
    public function setPhotoHash($PhotoHash)
    {
        $this->photoHash = $PhotoHash;

        return $this;
    }

    /**
     * Get PhotoHash
     *
     * @return string
     */
    public function getPhotoHash()
    {
        return $this->photoHash;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return AccountPhoto
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return AccountPhoto
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set isChild
     *
     * @param boolean $isChild
     *
     * @return AccountPhoto
     */
    public function setIsChild($isChild)
    {
        $this->isChild = $isChild;

        return $this;
    }

    /**
     * Get isChild
     *
     * @return bool
     */
    public function getIsChild()
    {
        return $this->isChild;
    }

    /**
     * Set isRegistered
     *
     * @param boolean $isRegistered
     *
     * @return AccountPhoto
     */
    public function setIsRegistered($isRegistered)
    {
        $this->isRegistered = $isRegistered;

        return $this;
    }

    /**
     * Get isRegistered
     *
     * @return bool
     */
    public function isRegistered()
    {
        return $this->isRegistered;
    }

    /**
     * Set amountLimit
     *
     * @param integer $amountLimit
     *
     * @return AccountPhoto
     */
    public function setAmountLimit($amountLimit)
    {
        $this->amountLimit = $amountLimit;

        return $this;
    }

    /**
     * Get amountLimit
     *
     * @return int
     */
    public function getAmountLimit()
    {
        return $this->amountLimit;
    }

    /**
     * Set disabledTo
     *
     * @param \DateTime $disabledTo
     *
     * @return AccountPhoto
     */
    public function setDisabledTo($disabledTo)
    {
        $this->disabledTo = $disabledTo;

        return $this;
    }

    /**
     * Get disabledTo
     *
     * @return \DateTime
     */
    public function getDisabledTo()
    {
        return $this->disabledTo;
    }

    /**
     * Set disabled
     *
     * @param boolean $disabled
     *
     * @return AccountPhoto
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled
     *
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled || new \DateTime() < $this->getDisabledTo();
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->firstName . '_' . $this->lastName;
    }
}

