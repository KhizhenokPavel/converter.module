<?php

namespace Pavelkhizhenok\Converter;

use Pavelkhizhenok\SupportClasses\Classes\ApiClient;

class FreeCurrencyApiClient extends ApiClient {

    protected string $apiKey = '';

    public function __construct(string $apiKey) {

        $this->apiKey = $apiKey;

        parent::__construct($this->getConfig());

    }

    public function getConfig(): array {

        return array(
            'url' => 'api.freecurrencyapi.com/v1/',
            'params' => array(
                'apikey' => $this->apiKey,
            ),
        );

    }

    public function getLatestExchangeRates(string $baseCurrency = '', array $currencies = array()): array|bool {

        return $this->sendRequest('latest', 
            array(
                'base_currency' => $baseCurrency,
                'currencies' => $this->getCurrenciesList($currencies),
            )
        );

    }

    public function getCurrencies(array $currencies = array()): array|bool {

        return $this->sendRequest('currencies',
            array(
                'currencies' => $this->getCurrenciesList($currencies),
            )
        );

    }

    public function getHistoricalExchangeRates(string $date, string $baseCurrency = '', array $currencies = array()): array|bool {

        return $this->sendRequest('historical', 
            array(
                'date' => $date,
                'base_currency' => $baseCurrency,
                'currencies' => $this->getCurrenciesList($currencies),
            )
        );

    }

    public function getExchangeRates(array $currencies = array()) {

        $rates = array();

        foreach ($currencies as $currency) {
            $exchangeRates = $this->getLatestExchangeRates($currency, $currencies);

            if ($exchangeRates) {
                $rates[$currency] = $exchangeRates['response'];
            }
        }

        return $rates;

    }

    protected function getCurrenciesList(array $currencies): string {
        
        if (!empty($currencies)) {
            return implode(',', $currencies);
        }

        return '';

    }

    public function getResponseByJson(string $response): array|bool {

        $response = parent::getResponseByJson($response);

        if (!isset($response['data'])) {
            return false;
        }

        return $response['data'];

    }

}