<?php

namespace AppBundle\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "user.validation.password.current_password.wrong_value", groups={"change_password"}
     * )
     */
    public $oldPassword;

    /**
     * @Assert\NotBlank(message="user.validation.password.notblank", groups={"change_password", "change_remind_password"})
     * @Assert\Length(
     *     min=7,
     *     minMessage="user.validation.password.min_length",
     *     max=50,
     *     maxMessage="user.validation.password.max_length",
     *     groups={"change_password", "change_remind_password"}
     * )
     */
    public $newPassword;

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }
}
