<?php
declare(strict_types = 1);

namespace Diaclone\Resource;

use Diaclone\Transformer\AbstractTransformer;

class Item extends AbstractResource
{
    public function transform(AbstractTransformer $transformer)
    {
        if ($propertyName = $this->getPropertyName()) {
            $data = $transformer->getPropertyValue($this->getData(), $this->getPropertyName());
        } else {
            $data = $this->getData();
        }
        $mappedProperties = $transformer->getMappedProperties();
        $includeAll = false;
        $response = [];
        if ($this->fieldMap === '*') {
            $includeAll = true;
            $fieldMap = array_keys($mappedProperties);
        }
        foreach ($fieldMap as $property) {
            // todo: exception if value isn't mapped

            if ($includeAll) {
                $propertiesToTransform = '*';
            }
            $dataTypeClass = $transformer->getDataType($property);
            $dataType = new $dataTypeClass($data, $property, $propertiesToTransform);

            $propertyTransformerClass = $transformer->getPropertyTransformer($property);
            $propertyTransformer = new $propertyTransformerClass();

            $response[$mappedProperties[$property]] = $propertyTransformer->transform($dataType);
        }

        return $response;
    }

    public function untransform(AbstractTransformer $transformer)
    {
        $mapping = array_flip($transformer->getMappedProperties());

        $response = [];
        foreach ($this->getData() as $incomingProperty => $data) {
            $property = $mapping[$incomingProperty];

            $dataTypeClass = $transformer->getDataType($property);
            $dataType = new $dataTypeClass($data, $property);

            $propertyTransformerClass = $transformer->getPropertyTransformer($property);
            $propertyTransformer = new $propertyTransformerClass();

            $response[$property] = $propertyTransformer->untransform($dataType);
        }

        return $response;
    }
}