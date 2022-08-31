<?php
namespace App\Application\Actions;

use Slim\Psr7\Response;
use Slim\Psr7\Request;

class FlagQuizzController extends AbstractController {
    const ANSWER_COUNT = 10;

    public function showQuestion (Request $request, Response $response) {
        $countryRepo = $this->getCountryRepository();

        $answers = $countryRepo->getRandomCountries(self::ANSWER_COUNT); // Generate a list of unique Country options
        $country = $answers[0];

        usort($answers, [$countryRepo, "sortCountries"]); // Sort the countries by name
        
        $data = [
            'country' => $country,
            'answers' => $answers,
        ];
        return $this->getView()->render($response, 'quizz/question.phtml', $data);
    }

    public function processAnswer (Request $request, Response $response) {
        $countryRepo = $this->getCountryRepository();
        $body = $request->getParsedBody();

        $data = [
            'givenAnswer' => $countryRepo->findByAlpha2($body['answer']),
            'correctAnswer' => $countryRepo->findByAlpha2($body['country']),
            'isCorrect' => $body['answer'] === $body['country'],
        ];

        return $this->getView()->render($response, 'quizz/answer.phtml', $data);
    }
}
