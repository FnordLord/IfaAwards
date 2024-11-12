<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\PirepAccepted;
use Modules\IfaAwards\Listeners\CheckPilotDistanceAwards;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PirepAccepted::class => [
            CheckPilotDistanceAwards::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
