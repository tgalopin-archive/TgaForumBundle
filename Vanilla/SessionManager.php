<?php

namespace Tga\ForumBundle\Vanilla;

/**
 * Vanilla session manager
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class SessionManager
{
    /**
     * Log in a Vanilla user based in its identifier (in the Vanilla table)
     *
     * @param int $userId
     */
    public function login($userId)
    {
        \Gdn::Session()->Start($userId, true, true);
    }

    /**
     * Log out the current user from Vanilla
     */
    public function logout()
    {
        \Gdn::Session()->End();
    }
}
