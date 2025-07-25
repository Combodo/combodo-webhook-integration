<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5c648c5cddb3c29c87aec82f31f7e556
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Combodo\\iTop\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Combodo\\iTop\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Combodo\\iTop\\Core\\Notification\\Action\\Webhook\\Exception\\WebhookInvalidJsonValueException' => __DIR__ . '/../..' . '/src/Core/Notification/Action/Webhook/Exception/WebhookInvalidJsonValueException.php',
        'Combodo\\iTop\\Core\\Notification\\Action\\_ActionWebhook' => __DIR__ . '/../..' . '/src/Core/Notification/Action/_ActionWebhook.php',
        'Combodo\\iTop\\Core\\WebRequest' => __DIR__ . '/../..' . '/src/Core/WebRequest.php',
        'Combodo\\iTop\\Core\\WebResponse' => __DIR__ . '/../..' . '/src/Core/WebResponse.php',
        'Combodo\\iTop\\Service\\CallbackService' => __DIR__ . '/../..' . '/src/Service/CallbackService.php',
        'Combodo\\iTop\\Service\\WebRequestSender' => __DIR__ . '/../..' . '/src/Service/WebRequestSender.php',
        'Combodo\\iTop\\Service\\WebRequestService' => __DIR__ . '/../..' . '/src/Service/WebRequestService.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5c648c5cddb3c29c87aec82f31f7e556::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5c648c5cddb3c29c87aec82f31f7e556::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5c648c5cddb3c29c87aec82f31f7e556::$classMap;

        }, null, ClassLoader::class);
    }
}
