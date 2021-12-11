Libraries about Openapi
=======================

This repository is under heavy development and not usable at all to this point. I collect some ideas and proof of concept here.

models
------

A library containing all types in Openapi. Here shouldn't be much logic at all.

json-tree
---------

A small library to convert json and yaml to a readable tree.

Dependencies:
- ext-json
- symfony/yaml

parser
------

A library to convert a tree from `json-tree` to models from `model`. This parser should be

- strict in terms of missing/additional properties and so on
- as informative as possible, if a tree could not be converted to an openapi-model
- easy understandable and therefore maintainable
- comparable to swaggers parser (see https://github.com/swagger-api/swagger-parser/blob/434714b2f543b36ad9b3c5f0f5d1cb2aa5d240eb/modules/swagger-parser-v3/src/main/java/io/swagger/v3/parser/util/OpenAPIDeserializer.java)

validator
---------

What league/openapi-psr7-validator to cebe/php-openapi is, `validator` can be to `models`. This parser should:

- work with the plain models and psr7 messages
- be as informative as possible; like the parser, the validator must return as much violations as possible
- easy understandable and therefore maintainable


code-generator
--------------

Idea: Take an OpenApi-Model from models and generate php-code. All models (schemas, request, responses) could be pre-generated. Also
it could generate interfaces (like a function(Request): Response) foreach path in schemas defined. Those should then be
integratable to psr-15 middlewares, symfony and other major frameworks.

php-dsl
-------

A dsl to generate an openapi object in a declarative way. This could provide faster feedback while editing a definition. Something like

```php
$api = openapi(
    version: '3.1.0',
    info: info(
        title: 'My superduper API',
        version: '0.0.1'
    ),
    components: components(
        /** And so on, you get the idea */
    )
)
```
