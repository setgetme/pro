<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Address.
 *
 * @ORM\Embeddable()
 */
class Address
{
    /**
     * @ORM\Column(name="post_code", type="text", length=255, nullable = true)
     */
    protected $postCode;

    /**
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    protected $address;

    /**
     * Set postCode.
     *
     * @param int $postCode
     *
     * @return $this
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * Get postCode.
     *
     * @return int
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }
}
