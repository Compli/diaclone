![](https://s3-us-west-2.amazonaws.com/picr-public/assets/api/diaclone.jpg)

# diaclone
Transform your data

## Installation

```bash
composer require picr/diaclone
```

## Usage

### Dynamic Transformer

```php

class PageTransformer extends DyanmicTransformer
{
    protected $mapping = [
        'age' => IntegerTransformer::class,
        'information_block' => [
            'transformation' => ArrayTransformer::class,
        ],
        'content' => [],
        'content_tags' => [
            'transformer' => TagTransformer::class,
            'isCollection' => true,
        ],
        'title' => [],
        'metadata' => [],
    ];
    
    protected $typeMapping = [
        'object' => [
            'class' => Page::class,
            'mapping' => [
                'content_tags' => [
                    'name' => 'tags',
                ],
                'information_block' => [
                    'name' => 'informationBlock',
                    'transformer' => InformationBlock::class,
                ],
            ],
            'exclude' => [
                'metadata',
            ],
        ],
        'response' => [ // since no class is set, it returns an array
            'mapping' => [
                'content' => [
                    'transformer' => [
                        StringTransformer::class, 
                        EmojiTransformer::class, 
                        OutputOnlyTransformer::class,
                    ],
                ],
            ],    
        ],
    ];
}
```

`isCollection` is optional and defaults to `false`.
`transformer` is optional and defaults to `StringTransformer`
if `transformer` is an array, then the transformers are chained.

the `name` in `$typeMapping` will set the property name to the value of `name`


### Using Dynamic Transformer
```php
$resource = new ObjectItem($data);
$transformer = new PageTransformer();

$responseData = $transformer->transform($resource)->properties([*])->from('object')->to('response');
```

`properties()` is optional and will default to all properties in the `to` type.
if `from()` is omitted, it assumes an array matching the properties in `$mapping` 

```php
$resource = new Item($data);
$transformer = new PageTransformer();

$object = $transformer->transform($resource)->to('object');
```