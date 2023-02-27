<?php
declare(strict_types=1);

namespace App\Application\DTO\Response\Transformer;

abstract class ResponseDtoTransformer implements ResponseDtoTransformerInterface
{
    public function transformFromObject($object)
    {
        return $object;
    }

    public function transformFromObjects(iterable $objects): iterable
    {
        $dto = [];
        foreach ($objects as $object) {
            $dto[] = $this->transformFromObject($object);
        }
        return $dto;
    }
}
{

}
