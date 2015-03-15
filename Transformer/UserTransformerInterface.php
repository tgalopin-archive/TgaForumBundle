<?php

namespace Tga\ForumBundle\Transformer;

use Symfony\Component\Security\Core\User\UserInterface;
use Tga\ForumBundle\Model\VanillaUser;

/**
 * This interface is implemented by all the transformer that create Vanilla users
 * using Symfony ones.
 * Each User entity should have its own implementation.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
interface UserTransformerInterface
{

    /**
     * Create a Vanilla user using a Symfony given one.
     * Must return a VanillaUser instance.
     *
     * @param UserInterface $user
     * @return VanillaUser
     */
    public function createVanillaUser(UserInterface $user);

}
