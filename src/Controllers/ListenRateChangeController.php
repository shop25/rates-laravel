<?php

namespace S25\RatesApiLaravel\Controllers;

use Illuminate\Contracts\Events\Dispatcher;
use S25\RatesApiLaravel\Events\RateChange;
use S25\RatesApiLaravel\Requests\RateChangeRequest;

class ListenRateChangeController
{
    public function __invoke(RateChangeRequest $request, Dispatcher $dispatcher)
    {
        $events = array_reduce(
            $request->getChanges(),
            static function ($events, $change) {
                $prevCode = $change['prev'];
                $nextCode = $change['next'];
                $events[] = new RateChange($prevCode, $nextCode, $change['direct']);
                $events[] = new RateChange($nextCode, $prevCode, $change['inverse']);
                return $events;
            },
            []
        );

        foreach ($events as $event) {
            $dispatcher->dispatch($event);
        }
    }
}