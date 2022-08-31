<?php
namespace App\Application\Actions;

use Slim\Psr7\Response;
use Slim\Psr7\Request;

class RoundTableController extends AbstractController {
    public function showIndex (Request $request, Response $response): Response {
        return $this->getView()->render($response, 'round-table/index.phtml');
    }

    public function getCountriesJson (Request $request, Response $response): Response {
        $queryParams = $request->getQueryParams();
        $countryRepo = $this->getCountryRepository();
        

        // Can generate n random countries if query param 'random' is passed
        if (isset($queryParams['random'])) {
            $selectedCountries = $countryRepo->getRandomCountries($queryParams['random']);
        } else {
            $selectedCountries = $countryRepo->findAll();
        }

        $formattedCountries = [];

        foreach ($selectedCountries as $country) {
            $formattedCountries[] = $country->formatJson();
        }

        $response->getBody()->write(json_encode($formattedCountries));
        return $response->withHeader("Content-Type", "application/json");
    }
}
