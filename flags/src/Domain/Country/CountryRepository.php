<?php
declare(strict_types=1);

namespace App\Domain\Country;

class CountryRepository
{
    /**
     * @var Country[]
     */
    private $countries = [];

    public function __construct()
    {
        $skipHeader = true;
        $f = fopen(__DIR__ . '/../../../wikipedia-iso-country-codes.csv', 'r');
        while (!feof($f)) {
            $row = fgetcsv($f);
            if ($row && !$skipHeader) {
                $this->countries[] = Country::fromCsvRow($row);
            }
            $skipHeader = false;
        }
    }

    public function findAll(): array
    {
        return $this->countries;
    }

    public function findByAlpha2($alpha2): Country
    {
        foreach ($this->countries as $country) {
            if ($country->alpha2 == $alpha2) {
                return $country;
            }
        }

        return null;
    }

    public function getByIndex ($index): Country {
        return $this->countries[$index];
    }

    public function getCount () {
        return count($this->countries);
    }

    public function getRandomCountry (): Country {
        $index = rand(0, $this->getCount() - 1);
        return $this->getByIndex($index);
    }

    public function getRandomCountries($numberToGenerate): array
    {
        $numberToGenerate = min($numberToGenerate, $this->getCount());
        $countries = [];
        $countryAlpha2 = [];
        while (count($countries) < $numberToGenerate)
        {
            $newCountry = $this->getRandomCountry();
            if (!in_array($newCountry->alpha2, $countryAlpha2)) {
                $countries[] = $newCountry;
                $countryAlpha2[] = $newCountry->alpha2;
            }
        }
        return $countries;
    }

    public static function sortCountries($countryA, $countryB) {
        if ($countryA->name == $countryB->name) {
            return 0;
        }
        return ($countryA->name < $countryB->name) ? -1 : 1;
    }
}
