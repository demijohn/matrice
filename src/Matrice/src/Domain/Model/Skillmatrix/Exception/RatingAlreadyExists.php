<?php
declare(strict_types=1);

namespace Matrice\Domain\Model\Skillmatrix\Exception;

use Matrice\Domain\Model\Skillmatrix\Rating;
use Matrice\Domain\Model\Skillmatrix\SkillmatrixId;
use Mezzio\ProblemDetails\Exception\CommonProblemDetailsExceptionTrait;
use Mezzio\ProblemDetails\Exception\ProblemDetailsExceptionInterface;
use RuntimeException;

class RatingAlreadyExists extends RuntimeException implements ProblemDetailsExceptionInterface
{
    use CommonProblemDetailsExceptionTrait;

    private const STATUS = 409;
    private const TYPE = '';
    private const TITLE = '';

    public static function create(SkillmatrixId $skillmatrixId, Rating $rating): self
    {
        $message = sprintf(
            'Skillmatrix "%s" already contains rating for Person "%s" and Skill "%s"',
            $skillmatrixId,
            $rating->getPersonId(),
            $rating->getSkillId()
        );

        return new self($message);
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
