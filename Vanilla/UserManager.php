<?php

namespace Tga\ForumBundle\Vanilla;

use Symfony\Component\Security\Core\User\UserInterface;
use Tga\ForumBundle\Model\VanillaUser;

/**
 * Vanilla user manager
 * Use the core UserModel and define an additional method to regsiter programmatically users
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class UserManager extends \UserModel
{
    /**
     * @param UserInterface $user
     * @return bool
     */
    public function findByUser(UserInterface $user)
    {
        return $this->findByUsername($user->getUsername());
    }

    /**
     * @param string $username
     * @return bool
     */
    public function findByUsername($username)
    {
        if ($vanillaUser = $this->GetByUsername($username)) {
            return (int) $vanillaUser->UserID;
        }

        return false;
    }

    /**
     * @param UserInterface $user
     */
    public function trackVisit(UserInterface $user)
    {
        $this->UpdateVisit($this->findByUser($user));
    }

    /**
     * @param VanillaUser $user
     * @return int
     */
    public function register(VanillaUser $user)
    {
        $fields = [
            'Name' => $user->getName(),
            'Email' => $user->getEmail(),
            'Password' => $user->getPassword(),
            'ShowEmail' => (int) $user->isShowEmail(),
            'DateFirstVisit' => $user->getDateFirstVisit()->format('U'),
            'DateLastActive' => $user->getDateLastActive()->format('U'),
            'DateInserted' => $user->getDateInserted()->format('U'),
            'LastIPAddress' => $user->getLastIPAddress(),
            'InsertIPAddress' => $user->getInsertIPAddress(),
            'Photo' => $user->getPhoto(),
            'Title' => $user->getTitle(),
            'Roles' => $user->getRoles(),
        ];

        $options = [
            'SaveRoles' => 1,
            'NoConfirmEmail' => 1,
        ];

        $uid = (int) $this->_Insert($fields, $options);

        $this->UpdateVisit($uid);

        return $uid;
    }

    /**
     * @param UserInterface $user
     * @return int
     */
    public function unregister(UserInterface $user)
    {
        return (int) $this->Delete($this->findByUser($user));
    }

    /**
     * @param UserInterface $user
     * @param bool $hideContent
     * @param bool $displayOnActivity
     * @param string $reason
     */
    public function ban(UserInterface $user, $hideContent = false, $displayOnActivity = false, $reason = '')
    {
        parent::Ban($this->findByUser($user), [
            'DeleteContent' => $hideContent,
            'AddActivity' => $displayOnActivity,
            'Reason' => $reason,
        ]);
    }

    /**
     * @param UserInterface $user
     * @param bool $showContent
     * @param bool $displayOnActivity
     * @param string $message
     */
    public function unban(UserInterface $user, $showContent = false, $displayOnActivity = false, $message = '')
    {
        parent::Ban($this->findByUser($user), [
            'RestoreContent' => $showContent,
            'AddActivity' => $displayOnActivity,
            'Story' => $message,
        ]);
    }
}
