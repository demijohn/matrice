<?php
declare(strict_types=1);

namespace Matrice\Library\Validation;

use Assert\AssertionFailedException;
use Assert\LazyAssertionException;
use Mezzio\ProblemDetails\Exception\CommonProblemDetailsExceptionTrait;
use Mezzio\ProblemDetails\Exception\ProblemDetailsExceptionInterface;

final class ValidationFailedException extends LazyAssertionException implements ProblemDetailsExceptionInterface
{
    use CommonProblemDetailsExceptionTrait;

    public function __construct(string $message, array $errors)
    {
        parent::__construct($message, $errors);

        $this->status = 422;
        $this->detail = $message;
        $this->title = 'Request Params Validation Failed';
        $this->type = '';

        $this->additional = [
            'errors' => array_reduce($errors, static function (array $errors, AssertionFailedException $error): array {
                $path = $error->getPropertyPath();
                $errors[$path][] = $error->getMessage();
                return $errors;
            }, []),
        ];
    }
}
