<?php
/** @noinspection PhpMissingFieldTypeInspection */

namespace S25\RatesApiLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Events\Dispatcher;
use S25\RatesApiClient\Contracts\Client;
use S25\RatesApiClient\Specs\RatesSpec;
use S25\RatesApiLaravel\Events\RateChange;

class ImportRates extends Command
{
    protected $signature = 'import:rates';

    protected $description = 'Imports rates from Currency Rates API';

    public function handle(Client $client, Dispatcher $dispatcher): void
    {
        $spec = RatesSpec::create()->setUsedAt(new \DateTimeImmutable());

        $rates = $client->requestRates()
            ->setSpec($spec)
            ->perform();

        foreach ($rates as $rate) {
            $rateChangeEvent = new RateChange($rate->baseCode, $rate->quoteCode, $rate->value);

            $dispatcher->dispatch($rateChangeEvent);
        }
    }
}
