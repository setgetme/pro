<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="email", message="user.validation.email.unique", groups={"registration"})
 */
class User implements UserInterface, AdvancedUserInterface
{
    const UPLOAD_DIR = 'uploads/avatars/';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $facebookId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="user.validation.firstname.notblank")
     * @Assert\Length(
     *     min=3, minMessage="user.validation.firstname.min_length",
     *     max=50, maxMessage="user.validation.firstname.max_length",
     * )
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="user.validation.lastname.notblank")
     */
    protected $lastName;

    /**
     * @ORM\Embedded(class="Address")
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    protected $city;

    /**
     * @Assert\Length(
     *     min = 8, minMessage = "user.validation.phone_number.min_length",
     *     max = 20, maxMessage = "user.validation.phone_number.max_length"
     * )
     * @ORM\Column(name="phone_number", type="string", length=255, nullable = true)
     */
    protected $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="user.validation.email.notblank", groups={"registration"})
     * @Assert\Email(groups={"registration"})
     */
    protected $email;

    /**
     * @Assert\NotBlank(message="user.validation.password.notblank", groups={"registration"})
     * @Assert\Length(
     *     min=7, minMessage="user.validation.password.min_length",
     *     max=50, maxMessage="user.validation.password.max_length",
     *     groups={"registration"}
     * )
     */
    protected $plainPassword;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $password;

    /**
     * @ORM\Column(name="roles", type="json_array")
     */
    protected $roles = [];

    /**
     * @ORM\Column(name="is_active", type="boolean", nullable = false)
     * @Assert\NotBlank(message="user.validation.isActive.notblank", groups={"admin_registration"})
     */
    protected $isActive;

    /**
     * @ORM\Column(name="action_token", type="string", length = 30, nullable = true)
     */
    protected $actionToken;

    /**
     * @ORM\Column(name="facebook_token", type="string", length = 255, nullable = true)
     */
    protected $facebookToken;

    /**
     * @ORM\Column(type="string", length = 100, nullable = true)
     */
    protected $avatar;

    /**
     * @Assert\Image(
     *      maxSize="5M",
     *      mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     *
     * @var UploadedFile
     */
    protected $avatarFile;

    protected $avatarTemp;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable = false))
     * @Assert\NotBlank(message="user.validation.terms_accepted_at.notblank",
     *     groups={"registration"})
     */
    protected $termsAcceptedAt;

    public function __construct()
    {
        $this->address = new Address();
        $this->isActive = false;
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param mixed $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    public function setAvatarFile(UploadedFile $avatarFile)
    {
        $this->updatedAt = new \DateTime();
        $this->avatarFile = $avatarFile;

        return $this;
    }

    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * Set avatar.
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return string
     */
    public function getAvatar()
    {
        if (null == $this->avatar) {
            return;
        }

        return static::UPLOAD_DIR.$this->avatar;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set plainPassword.
     *
     * @param string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get plainPassword.
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getRoles()
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    /**
     * Set roles.
     *
     * @param string $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set actionToken.
     *
     * @param string $actionToken
     *
     * @return User
     */
    public function setActionToken($actionToken)
    {
        $this->actionToken = $actionToken;

        return $this;
    }

    /**
     * Get actionToken.
     *
     * @return string
     */
    public function getActionToken()
    {
        return $this->actionToken;
    }

    /**
     * Set facebookToken.
     *
     * @param string $facebookToken
     *
     * @return User
     */
    public function setFacebookToken($facebookToken)
    {
        $this->facebookToken = $facebookToken;

        return $this;
    }

    /**
     * Get facebookToken.
     *
     * @return string
     */
    public function getFacebookToken()
    {
        return $this->facebookToken;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Set phoneNumber.
     *
     * @param string $phoneNumber
     *
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber.
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist()
    {
        if ($this->roles == 'ROLE_ADMIN') {
            $roles[] = 'ROLE_ADMIN';
        }

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        $this->setRoles($roles);
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preSave()
    {
        if (null !== $this->getAvatarFile()) {
            if (null !== $this->avatar) {
                $this->avatarTemp = $this->avatar;
            }

            $avatarName = sha1(uniqid(null, true));
            $this->setAvatar($avatarName.'.'.$this->getAvatarFile()->guessExtension());
        }
    }

    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function postSave()
    {
        if (null !== $this->getAvatarFile()) {
            $this->getAvatarFile()->move($this->getUploadRootDir(), $this->avatar);
            unset($this->avatarFile);
            if (null !== $this->avatarTemp) {
                unlink($this->getUploadRootDir().$this->avatarTemp);
                unset($this->avatarTemp);
            }
        }
    }

    public function getUploadRootDir()
    {
        return __DIR__.'/../../../web/'.static::UPLOAD_DIR;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Set termsAcceptedAt.
     *
     * @param \DateTime $termsAcceptedAt
     *
     * @return User
     */
    public function setTermsAcceptedAt($termsAcceptedAt)
    {
        $this->termsAcceptedAt = $termsAcceptedAt;

        return $this;
    }

    /**
     * Get termsAcceptedAt.
     *
     * @return \DateTime
     */
    public function getTermsAcceptedAt()
    {
        return $this->termsAcceptedAt;
    }

    public function isEnabled()
    {
        return $this->getIsActive();
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }
}
