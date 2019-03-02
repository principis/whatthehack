<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccountRepository")
 */
class Account
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
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="iban", type="string", length=255, unique=true, nullable=false)
     */
    private $iban;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="client", type="string", length=255, unique=true, nullable=false)
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="AccountPhoto", mappedBy="account")
     */
    private $accountPhotos;

    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="account")
     */
    private $transactions;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\Length(max="255")
     */
    private $password;


    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Account
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
     * @return Account
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
     * Set iban
     *
     * @param string $iban
     *
     * @return Account
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban
     *
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set client
     *
     * @param string $client
     *
     * @return Account
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Account
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
     * @return AccountPhoto[]
     */
    public function getAccountPhotos()
    {
        return $this->accountPhotos;
    }

    /**
     * @param AccountPhoto $accountPhoto
     */
    public function addAccountPhoto($accountPhoto) {
        if (!$this->accountPhotos->contains($accountPhoto)) {
            $this->accountPhotos[] = $accountPhoto;
            $accountPhoto->setAccount($this);
        }
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}

