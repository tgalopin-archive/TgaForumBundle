
TgaForumBundle
==============

What is that?
-------------

TgaForumBundle is a Symfony2 bundle aiming to synchronize Symfony2 with the forum software
[Vanilla](http://vanillaforums.org/).

Since it has been released, Symfony2 lacks of a complete, well-tested and powerful forum bundle.
But why create a new system that already exists in the wild?

Vanilla is a modern, flexible, framework-based forum software providing a complete community
platform. The aim of this bundle is simply to connect it to your Symfony application so you
can use Vanilla as an extension of Symfony.

### Install

> **Note:** This bundle synchronize **Vanilla with Symfony**, and not the contrary. It means you should
> redirect Vanilla subscription and login (with a simple server configuration for instance) to your
> Symfony one.

> **Note:** The bundle will create the account in Vanilla as they log into Symfony, if they don't exists
> in Vanilla database.


**Install Vanilla**

Install Vanilla in your public directory (`web`) by downloading it from the official
website and by running it into your browser.

Once installed, install for Symfony:

**Install Symfony bundle**

Using Composer : `composer require tga/forum-bundle`

Add this line to your `AppKernel.php` file : `new Tga\ForumBundle\TgaForumBundle(),`

You have to configure the bundle in your `config.yml` file:

``` yaml
tga_forum:
    vanilla_path: "%kernel.root_dir%/../web/<your_vanilla_path>
```

Now, every user connecting/disconnecting throw Symfony should be also connected/disconnected
in Vanilla.


### How does it work? The Vanilla Kernel

The Vanilla kernel is a service (`tga_forum.vanilla`) able to boot Vanilla in the current
context of Symfony. Once booted, all the feaures from Vanilla are available in Symfony.

For instance, it's used by the `AuthenticationSuccessHandler` to use Vanilla login methods.

Using the kernel, you have access to two methods : `getUserManager` and `getSessionManager`,
managing the users and the sessions.

For instance, the `AuthenticationSuccessHandler` (connect the users into Vanilla when they
connect in Symfony) uses the Kernel and the managers :

``` php
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
```
