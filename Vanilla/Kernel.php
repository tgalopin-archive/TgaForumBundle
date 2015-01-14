<?php

namespace Tga\ForumBundle\Vanilla;

/**
 * Kernel for Vanilla
 * Boot Vanilla from Symfony and interact with Garden (the Vanilla framework)
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Kernel
{
    /**
     * Bundle Vanilla version
     */
    const VANILLA_VERSION = '2.7';

    /**
     * Vanilla forum path
     *
     * Required to boot properly
     *
     * @var string
     */
    protected $vanillaPath;

    /**
     * Vanilla users manager
     *
     * @var UserManager
     */
    protected $userManager;

    /**
     * Vanilla sessions manager
     *
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * Is Vanilla booted?
     *
     * @var bool
     */
    private $booted = false;

    /**
     * Constructor
     *
     * @param string $vanillaPath
     */
    public function __construct($vanillaPath)
    {
        $this->vanillaPath = $vanillaPath;
    }

    /**
     * Boot Vanilla
     */
    public function boot($debug = false)
    {
        define('APPLICATION', 'Vanilla');
        define('APPLICATION_VERSION', self::VANILLA_VERSION);
        define('DS', DIRECTORY_SEPARATOR);
        define('PATH_ROOT', $this->vanillaPath);

        if ($debug) {
            error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);
        } else {
            error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR);
        }

        require PATH_ROOT . '/bootstrap.php';

        $this->userManager = new UserManager();
        $this->sessionManager = new SessionManager();
    }

    /**
     * Is Vanilla booted?
     *
     * @return bool
     */
    public function isBooted()
    {
        return $this->booted;
    }

    /**
     * @return UserManager
     */
    public function getUserManager()
    {
        if (! $this->isBooted()) {
            $this->boot();
        }

        return $this->userManager;
    }

    /**
     * @return SessionManager
     */
    public function getSessionManager()
    {
        if (! $this->isBooted()) {
            $this->boot();
        }

        return $this->sessionManager;
    }

    /**
     * @return string
     */
    public function getVanillaPath()
    {
        return $this->vanillaPath;
    }
}
