<?php

namespace Tga\ForumBundle\Transformer;

use Symfony\Component\Security\Core\User\UserInterface;
use Tga\ForumBundle\Model\VanillaUser;

/**
 * Default transformer: does not do a lot, but enough to work
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class DefaultUserTransformer implements UserTransformerInterface
{
    /**
     * @param UserInterface $user
     * @return VanillaUser
     */
    public function createVanillaUser(UserInterface $user)
    {
        return new VanillaUser($user->getUsername());
    }
}
