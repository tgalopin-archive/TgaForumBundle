
TgaForumBundle
==============

TgaForumBundle is a Symfony2 bundle aiming to synchronize Symfony2 with the forum software
[Vanilla 2](http://vanillaforums.org/).

Since it has been released, Symfony2 lacks of a complete, well-tested and powerful forum bundle.
But why create a new system that already exists in PHP?

Vanilla is a modern, flexible, framework-based forum software providing a complete community
platform. The aim of this bundle is to connect it to your Symfony application so you
can use Vanilla as an extension of Symfony.


Installation
------------

> **Note:** This bundle synchronize **Vanilla with Symfony**, and not the contrary: Symfony is the master,
> Vanilla is the slave. It means you should redirect Vanilla subscription and login (with a simple server
> configuration for instance) to your Symfony one.

> **Note:** The bundle will create a Vanilla user when a user unknown by Vanilla log into Symfony successfully.
> The created user will have the exact same username in Symfony and Vanilla (that's the matching field).


### Install Vanilla

Install Vanilla in your public directory (`web`) by downloading it from the official
website and by running it into your browser.

Once fully installed, install the bundle.



### Install the Symfony bundle

**1. Download it using Composer**

`composer require tga/forum-bundle`


**2. Register it**

Edit your `AppKernel.php` file:

``` php
<?php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Tga\ForumBundle\TgaForumBundle(),
            // ...
        );
    }
}
```


**3. Configure it**

Edit your `config.yml` file to register where is stored Vanilla:

``` yaml
tga_forum:
    vanilla_dir: %kernel.root_dir%/../web/<your_vanilla_path>
```


**4. Register the security handlers**

Edit the `security.yml` configuration file to handle the login/logout successes:

``` yaml
security:
    firewalls:
        main:
            form_login:
                success_handler: tga_forum.authentication_success_handler
            logout:
                success_handler: tga_forum.logout_success_handler
```


**4. And you are done**

Now, every user connecting/disconnecting throw Symfony should be also connected/disconnected
in Vanilla.

> **Note**: If you have errors with Doctrine not able to update the schema because of Vanilla
> tables, you can use the tables filter in the Doctrine configuration:
>
> ``` yaml
> doctrine:
>     dbal:
>         schema_filter: ~^(?!GDN_)~
> ```



### The user transformers

The bundle use the concept of UserTransformer to build Vanilla users from Symfony security token.
It's an optionnal process you can do if you want to customize the Vanilla users created by the
bundle. Declaring your own transformer, you can choose the inserted data.

**Create a custom transformer**

User transformers must implements interface `Tga\ForumBundle\Transformer\UserTransformerInterface`.
You can use the DefaultUserTransformer to start your own:

``` php
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
```

The method `createVanillaUser()` is called by the login success handler to transform a Symfony user in
a Vanilla one.

**Use the transformer**

Declare it as a service and register it in the bundle configuration:

``` yaml
services:
    my_user_transformer:
        class: Acme\UserBundle\Transformer\MyUserTransformer
        
tga_forum:
    vanilla_dir: %kernel.root_dir%/../web/<your_vanilla_path>
    user_transformer: my_user_transformer
```


### How does it work? The Vanilla Kernel

The Vanilla kernel is a service (`tga_forum.vanilla`) able to boot Vanilla in the current
context of Symfony. Once booted, all the feaures from Vanilla are available in Symfony.

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
