<?php

namespace Tga\ForumBundle\Transformer;

use Symfony\Component\Security\Core\User\UserInterface;
use Tga\ForumBundle\Model\VanillaUser;

/**
 * FOS user transformer: transformer for FOSUserBundle users
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class FOSUserTransformer implements UserTransformerInterface
{
    /**
     * @param UserInterface $user
     * @return VanillaUser
     */
    public function createVanillaUser(UserInterface $user)
    {
        /** @var $user \FOS\UserBundle\Model\User */

        $vanillaUser = new VanillaUser($user->getUsername());
        $vanillaUser->setEmail($user->getEmail());

        if ($user->isSuperAdmin()) {
            $vanillaUser->setRoles([ VanillaUser::ROLE_MEMBER, VanillaUser::ROLE_ADMINISTRATOR ]);
        }

        return $vanillaUser;
    }
}
