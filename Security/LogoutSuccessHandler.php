<?php

namespace Tga\ForumBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler;

use Tga\ForumBundle\Vanilla\Kernel;

/**
 * Vanilla logout success handler
 *
 * Add the Vanilla logout to the default logout success handler
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class LogoutSuccessHandler extends DefaultLogoutSuccessHandler
{
    /**
     * Vanilla kernel used to interact with Vanilla core
     *
     * @var Kernel
     */
    protected $vanillaKernel;

    /**
     * @param Kernel $vanillaKernel
     * @param HttpUtils $httpUtils
     * @param string $targetUrl
     */
    public function __construct(Kernel $vanillaKernel, HttpUtils $httpUtils, $targetUrl = '/')
    {
        parent::__construct($httpUtils, $targetUrl);

        $this->vanillaKernel = $vanillaKernel;
    }

    /**
     * Log out the Vanilla user and continue to the default behavior
     *
     * @param Request        $request
     *
     * @return Response never null
     */
    public function onLogoutSuccess(Request $request)
    {
        $this->vanillaKernel->getSessionManager()->logout();

        return parent::onLogoutSuccess($request);
    }
}
