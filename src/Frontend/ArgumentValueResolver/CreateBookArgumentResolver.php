<?php

declare(strict_types=1);

namespace App\Frontend\ArgumentValueResolver;

use App\Backend\Application\Command\CreateNewBook\CreateNewBook;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Uid\Uuid;

class CreateBookArgumentResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        $isSupported = $argumentType
            && (
                $argumentType === CreateNewBook::class
                || is_subclass_of($argumentType, CreateNewBook::class)
            );

        if (!$isSupported) {
            return [];
        }

        try {
            $body = $request->toArray();
        } catch (JsonException $e) {
            throw new BadRequestHttpException(
                'body is not valid json',
                $e
            );
        }

        $uuid = Uuid::v4();

        try {
            $releaseDate = \DateTimeImmutable::createFromFormat('Y-m-d', (string) $body['releaseDate']);
            if (!$releaseDate) {
                throw new \InvalidArgumentException('Invalid date format. Expecting "YYY-mm-dd"');
            }

            $command = new CreateNewBook(
                $uuid,
                (string) $body['title'],
                (string) $body['author'],
                (int) $body['pages'],
                $releaseDate,
            );
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException(
                'body is not valid: '. $e->getMessage(),
                $e
            );
        }

        return [$command];
    }
}
