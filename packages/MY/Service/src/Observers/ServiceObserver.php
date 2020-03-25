<?php


namespace MY\Service\Observers;


use Illuminate\Support\Facades\Storage;

class ServiceObserver
{
    /**
     * Handle the Service "deleted" event.
     *
     * @param  \MY\Service\Contracts\Service  $service
     * @return void
     */
    public function deleted($service)
    {
        Storage::deleteDirectory('service/' . $service->id);
    }
}