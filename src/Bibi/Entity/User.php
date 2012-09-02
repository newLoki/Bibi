<?php
namespace Bibi\Entity;

/**
 * @Entity(repositoryClass="Bibi\Repo\UserRepo")
 * @Table(name="users")
 */
class User extends Base
{
    /** Date format for user birthdate */
    const DATE_BIRTH = 'Y-m-d';

    /**
     * @Column(type="string")
     * @var string
     **/
    protected $surname;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $lastname;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $email;

    /**
     * @Column(type="date")
     * @var \DateTime
     */
    protected $birthdate;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * set birthdate for user
     *
     * @param \DateTime $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = \DateTime::createFromFormat(self::DATE_BIRTH, $birthdate);
    }

    /**
     * get the birthdate for this user
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate->format(self::DATE_BIRTH);
    }

    /**
     * Set users email address
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get users email address
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Get user id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lastname for user
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname of user
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * set password for user
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get user password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Setter for surname
     *
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Getter for surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}