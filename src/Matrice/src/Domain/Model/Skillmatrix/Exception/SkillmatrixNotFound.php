<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix\Exception;

use Matrice\Domain\Model\Skillmatrix\SkillmatrixId;
use Mezzio\ProblemDetails\Exception\CommonProblemDetailsExceptionTrait;
use Mezzio\ProblemDetails\Exception\ProblemDetailsExceptionInterface;
use RuntimeException;

class SkillmatrixNotFound extends RuntimeException implements ProblemDetailsExceptionInterface
{
    use CommonProblemDetailsExceptionTrait;

    private const STATUS = 404;
    private const TYPE = '';
    private const TITLE = '';

    public static function create(SkillmatrixId $skillmatrixId): self
    {
        return new self(sprintf('Skillmatrix "%s" not found', $skillmatrixId));
    }

    private function __construct(string $message = '')
    {
        parent::__construct($message);

        $this->status = self::STATUS;
        $this->detail = $message;
        $this->title = self::TITLE;
        $this->type = self::TYPE;
    }
}
