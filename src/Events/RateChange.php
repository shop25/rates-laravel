<?php

namespace S25\RatesApiLaravel\Events;

class RateChange
{
    public string $baseCode;
    public string $quoteCode;
    public float  $value;

    public function __construct(string $baseCode, string $quoteCode, float $value)
    {
        $this->baseCode = $baseCode;
        $this->quoteCode = $quoteCode;
        $this->value = $value;
    }
}
