<?php

declare(strict_types=1);

namespace App\Tests\functional;

use App\Backend\Domain\Entity\Book;
use App\Tests\Fixture\BooksFixture;
use App\Tests\FunctionalTester;
use Symfony\Component\Uid\Uuid;

class BookCest
{
    public function createBookPositive(FunctionalTester $I): void
    {
        $author = "John Baddiel3";
        $postBody = [
            "title" => "The Some Name Agency",
            "author" => "John Baddiel3",
            "pages" => 150,
            "releaseDate" => "2024-09-03"
        ];
        $apiKey = $I->grabParameter('app.api_key');

        $I->haveHttpHeader('Content-type', 'application/json');
        $I->haveHttpHeader('X-Api-Key', $apiKey);
        $response = $I->sendPost(
            '/v1/books',
            $postBody
        );

        $I->seeResponseCodeIs(202);
        $I->seeResponseJsonMatchesJsonPath('.id');
        $responseDecoded = json_decode($response, true);

        $bookId = Uuid::fromString($responseDecoded['id']);
        $book = $I->grabEntityFromRepository(
            Book::class,
            ['id' => $bookId->toBinary()]
        );

        $I->assertSame($author, $book->getAuthor()->getName());
    }

    public function createBookWithNoApiKeyNegative(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Content-type', 'application/json');
        $I->sendPost(
            '/v1/books',
            []
        );

        $I->seeResponseCodeIs(401);
    }

    public function createBookWithWrongApiKeyNegative(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Content-type', 'application/json');
        $I->haveHttpHeader('X-Api-Key', 'adasdadsasdasdads');
        $I->sendPost(
            '/v1/books',
            []
        );

        $I->seeResponseCodeIs(401);
    }

    public function createBookWithInvalidJsonNegative(FunctionalTester $I): void
    {
        $apiKey = $I->grabParameter('app.api_key');

        $I->haveHttpHeader('X-Api-Key', $apiKey);
        $I->sendPost(
            '/v1/books',
            '{asdasd'
        );

        $I->seeResponseCodeIs(400);
    }

    public function getBooksPositive(FunctionalTester $I): void
    {
        $booksDefinitions = [
            [
                "id" => Uuid::v4()->toRfc4122(),
                "title" => "The Some Name Agency",
                "author" => "John Baddiel",
                "pages" => 50,
                "releaseDate" => "1924-09-03"
            ],
            [
                "id" => Uuid::v4()->toRfc4122(),
                "title" => "The Some Name Agency2",
                "author" => "John Baddiel2",
                "pages" => 150,
                "releaseDate" => "2024-09-03"
            ],
        ];
        $apiKey = $I->grabParameter('app.api_key');

        $I->loadFixtures(
            new BooksFixture($booksDefinitions)
        );

        uasort(
            $booksDefinitions,
            static fn ($book1, $book2) =>
                \DateTimeImmutable::createFromFormat('Y-m-d', $book2['releaseDate']) <=> \DateTimeImmutable::createFromFormat('Y-m-d', $book1['releaseDate'])
        );

        $I->haveHttpHeader('Content-type', 'application/json');
        $I->haveHttpHeader('X-Api-Key', $apiKey);
        $response = $I->sendGet(
            '/v1/books',
        );

        $I->seeResponseCodeIs(200);
        $I->seeResponseJsonMatchesJsonPath('.data');
        $responseDecoded = json_decode($response, true);

        $I->assertEquals(
            array_values($booksDefinitions),
            $responseDecoded['data']
        );
    }
}
