<?php

namespace Tga\ForumBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;

use Tga\ForumBundle\Vanilla\Kernel;

/**
 * Vanilla authentication success handler
 *
 * Add the Vanilla connection to the default authentication success handler
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
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
     * @param array $options
     */
    public function __construct(Kernel $vanillaKernel, HttpUtils $httpUtils, array $options)
    {
        parent::__construct($httpUtils, $options);

        $this->vanillaKernel = $vanillaKernel;
    }

    /**
     * Connect the user to Vanilla and continue to the default behavior
     *
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $userManager = $this->vanillaKernel->getUserManager();
        $sessionManager = $this->vanillaKernel->getSessionManager();

        $vanillaUser = $userManager->findByUsername($token->getUsername());

        if ($vanillaUser) {
            $vanillaUserId = $vanillaUser;
        } else {
            $vanillaUserId = $userManager->register($token->getUser());
        }

        $sessionManager->login($vanillaUserId);
        $userManager->trackVisit($token->getUser());

        return parent::onAuthenticationSuccess($request, $token);
    }
}
