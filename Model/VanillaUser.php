<?php

namespace Tga\ForumBundle\Model;

/**
 * This interface is implemented by all the transformer that create Vanilla users
 * using Symfony ones.
 * Each User entity should have its own implementation.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class VanillaUser
{
    const ROLE_GUEST = 2;
    const ROLE_UNCONFIRMED = 3;
    const ROLE_APPLICANT = 4;
    const ROLE_MEMBER = 8;
    const ROLE_ADMINISTRATOR = 16;
    const ROLE_MODERATOR = 32;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $photo;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var bool
     */
    protected $showEmail;

    /**
     * @var \DateTime
     */
    protected $dateFirstVisit;

    /**
     * @var \DateTime
     */
    protected $dateLastActive;

    /**
     * @var \DateTime
     */
    protected $dateInserted;

    /**
     * @var string
     */
    protected $lastIPAddress;

    /**
     * @var string
     */
    protected $insertIPAddress;

    /**
     * @var array
     */
    protected $roles;

    /**
     * Constructor
     * Generate default values for the optionnal fields
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->email = substr(md5(uniqid()), 0, 5) . '@' . substr(md5(uniqid()), 0, 5) . '.com';
        $this->password = md5(uniqid(time(), true));
        $this->showEmail = false;
        $this->dateFirstVisit = new \DateTime();
        $this->dateLastActive = new \DateTime();
        $this->dateInserted = new \DateTime();
        $this->lastIPAddress = '127.0.0.1';
        $this->insertIPAddress = '127.0.0.1';
        $this->roles = [ self::ROLE_MEMBER ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = (string) $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     * @return $this
     */
    public function setPhoto($photo)
    {
        $this->photo = (string) $photo;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
        return $this;
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
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = (string) $password;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isShowEmail()
    {
        return $this->showEmail;
    }

    /**
     * @param boolean $showEmail
     * @return $this
     */
    public function setShowEmail($showEmail)
    {
        $this->showEmail = (bool) $showEmail;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateFirstVisit()
    {
        return $this->dateFirstVisit;
    }

    /**
     * @param \DateTime $dateFirstVisit
     * @return $this
     */
    public function setDateFirstVisit(\DateTime $dateFirstVisit)
    {
        $this->dateFirstVisit = $dateFirstVisit;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateLastActive()
    {
        return $this->dateLastActive;
    }

    /**
     * @param \DateTime $dateLastActive
     * @return $this
     */
    public function setDateLastActive(\DateTime $dateLastActive)
    {
        $this->dateLastActive = $dateLastActive;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateInserted()
    {
        return $this->dateInserted;
    }

    /**
     * @param \DateTime $dateInserted
     * @return $this
     */
    public function setDateInserted(\DateTime $dateInserted)
    {
        $this->dateInserted = $dateInserted;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastIPAddress()
    {
        return $this->lastIPAddress;
    }

    /**
     * @param string $lastIPAddress
     * @return $this
     */
    public function setLastIPAddress($lastIPAddress)
    {
        $this->lastIPAddress = (string) $lastIPAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getInsertIPAddress()
    {
        return $this->insertIPAddress;
    }

    /**
     * @param string $insertIPAddress
     * @return $this
     */
    public function setInsertIPAddress($insertIPAddress)
    {
        $this->insertIPAddress = (string) $insertIPAddress;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
        return $this;
    }
}
