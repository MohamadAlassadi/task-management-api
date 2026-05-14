<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    Modules\Notification\App\Providers\NotificationServiceProvider::class,
    Modules\Task\App\Providers\TaskUserServiceProvider::class,
    Modules\Team\App\Providers\TeamUserServiceProvider::class,
    Modules\Team\App\Providers\ProjectServiceProvider::class,
    Modules\Team\App\Providers\TeamServiceProvider::class,
    Modules\User\App\Providers\UserServiceProvider::class,
];
