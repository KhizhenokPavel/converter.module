<?php

namespace Pavelkhizhenok\Converter;

class Converter {

    protected array $availableExchangeRates = array();

    public function __construct(array $availableExchangeRates) {

        $this->setAvailableExchangeRates($availableExchangeRates);

    }

    public function convert(float $sum, string $from, string $to): float|bool {

        $rate = $this->getRate($from, $to);

        if (empty($rate)) {
            return false;
        }

        return $rate * $sum;

    }

    public function getRate(string $from, string $to): float|bool {

        if (empty($this->availableExchangeRates[$from])) {
            return false;
        }

        $currencyRates = $this->availableExchangeRates[$from];

        if (empty($currencyRates[$to])) {
            return false;
        }

        return $currencyRates[$to];

    }

    public function setAvailableExchangeRates(array $availableExchangeRates) {

        $this->availableExchangeRates = $availableExchangeRates;

    }

}