<?php
declare(strict_types = 1);

namespace Diaclone\Transformer;

use Diaclone\Resource\ResourceInterface;

class InputOnlyRawStringTransformer extends RawStringTransformer
{
    public function allowTransform(ResourceInterface $resource): bool
    {
        return false;
    }
}