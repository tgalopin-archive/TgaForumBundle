<?php

namespace Tga\ForumBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;

use Tga\ForumBundle\Model\VanillaUser;
use Tga\ForumBundle\Transformer\UserTransformerInterface;
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
     * User transformer to create Vanilla users using Symfony ones
     *
     * @var UserTransformerInterface
     */
    protected $userTransformer;

    /**
     * @param Kernel $vanillaKernel
     * @param UserTransformerInterface $userTransformer
     * @param HttpUtils $httpUtils
     * @param array $options
     */
    public function __construct(Kernel $vanillaKernel, UserTransformerInterface $userTransformer, HttpUtils $httpUtils, array $options)
    {
        parent::__construct($httpUtils, $options);

        $this->vanillaKernel = $vanillaKernel;
        $this->userTransformer = $userTransformer;
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
            $builtModel = $this->userTransformer->createVanillaUser($token->getUser());

            if (! $builtModel instanceof VanillaUser) {
                throw new \RuntimeException(sprintf(
                    '%s::createVanillaUser() must return a VanillaUser instance (%s given)',
                    get_class($this->userTransformer),
                    is_object($builtModel) ? get_class($builtModel) : gettype($builtModel)
                ));
            }

            $vanillaUserId = $userManager->register($builtModel);
        }

        $sessionManager->login($vanillaUserId);
        $userManager->trackVisit($token->getUser());

        return parent::onAuthenticationSuccess($request, $token);
    }
}
