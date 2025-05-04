# PeeQL
PeeQL (pronounced as 'pickle') stands for PHP (or PeeHP) Query Language. It allows converting and mapping JSON queries to PHP classes for database querying.

This project took inspiration from GraphQL but it is rather more basic.

This project is currently work in progress as well as this document.

## How does it work?
You import PeeQL to your project and initialize an instance of PeeQL (see `src/PeeQL.php`). From there you obtain a router and define routes to your repositories, models or other database operations handlers.

Then you can create your JSON queries (see _ADD_LINK_TO_THE_JSON_QUERY_TUTORIAL_SECTION_) and send them to the `PeeQL` class. `PeeQL` class will process the JSON query and return the result from the database.

## JSON queries
PeeQL expects input in JSON.

### Common attributes
#### Mandatory attributes
Each JSON must have these attributes:

- `operation`
    - defines the type of the operation
    - possible values are:
        - `query`
- `name`
    - defines the operation name
- `definition`
    - contains the operation definition
    - _`handler name`_
        - defines the name of the handler
        - _`handler method name`_
            - defines the name of the handler method
#### Optional attributes
There are also optional attributes:
- `description`
    - describes the operation

### Query-only attributes
#### Mandatory attributes
Query JSON must also have these attributes:

- `definition`._`handler name`_._`handler method name`_
    - `cols`
        - defines the names of the columns that will be returned

#### Optional attributes
There are also optional attributes:
- `definition`._`handler name`_._`handler method name`_
    - `conditions`
        - defines the list of condition that must be met
    - `orderBy`
        - defines the result ordering
    - `limit`
        - defines the limit of results returned
    - `page`
        - defines the page or offset of the results returned

### Examples
#### Get all documents
Let's assume we have a database table named `documents`. 

In that table there are these columns:
- `documentId`
- `title`
- `description`
- `authorUserId`
- `isVisible`
- `dateCreated`
- `dateModified`

We want to get `documentId`, `title` and `dateCreated` of all documents.

So, the JSON query would be:

```
{
    "operation": "query",
    "name": "getDocuments",
    "definition": {
        "documents": {
            "get": {
                "cols": [
                    "documentId",
                    "title",
                    "dateCreated"
                ]
            }
        }
    }
}
```

The handler (before routing) would be: `documents` and the method: `get()`.

#### Get all documents filtered and ordered
Let's assume we have a database table named `documents`. 

In that table there are these columns:
- `documentId`
- `title`
- `description`
- `authorUserId`
- `isVisible`
- `dateCreated`
- `dateModified`

We want to get `documentId`, `title`, `authorUserId` and `dateCreated` of all documents that have a certain author and are ordered by `dateCreated` in descending order.

So, the JSON query would be:
```
{
    "operation": "query",
    "name": "getDocuments",
    "definition": {
        "documents": {
            "get": {
                "cols": [
                    "documentId",
                    "title",
                    "authorUserId",
                    "dateCreated"
                ],
                "conditions": [
                    {
                        "col": "authorUserId",
                        "value": 10,
                        "type": "eq"
                    }
                ],
                "orderBy": {
                    "dateCreated": "DESC"
                }
            }
        }
    }
}
```

## Future plans
This is a list of future plans:
- Mutations
    - data modification
- Aggregation
    - result count
- Schema browser

## Setup
As this is a PHP library you need to implement it into your PHP project. Auto-loading the library files will not be covered in this setup.

__To-do list:__
1. First you need to put the PeeQL library files to your project.
2. Then you should create a wrapper class around the main `PeeQL\PeeQL` class. In this setup the wrapper class will be called `PeeQLWrapper`.
    - You may ask why should you create a wrapper class?
        - The answer is easy, it is better to have a wrapper class that will also contain schema definition and routing definition.
    - The wrapper must implement the `PeeQL\IPeeQLWrapperClass` interface.
3. The `PeeQLWrapper` class then should be instantiated.
4. Then you can put JSON queries to the `PeeQLWrapper::execute()` method.
5. The result will be returned in JSON.

### Route definition
Routes in PeeQL are links to the query-handling objects. These can be repositories, models, managers or anything else that works with any data.

Routes in PeeQL are defined using `PeeQL\Router\PeeQLRouter` class. This class has two methods for defining routes:
1. `PeeQL\Router\PeeQLRouter::addObjectRoute(string $name, object $object)`
    - $name is the handler name
        - In the JSON query the first attribute right after `definition` is the handler name
            - It is capitalized - "documents" -> "Documents"
    - $object is the handler object itself
2. `PeeQL\Router\PeeQLRouter::addRoute(string $name, string $className, array $params = [])`
    - $name is the handler name
        - In the JSON query the first attribute right after `definition` is the handler name
            - It is capitalized - "documents" -> "Documents"
    - $className is the name of the handler class -> retrieved by defining the class name and suffix `::class`
    - $params is an array of parameters that will be passed to $className when being instantiated

So, you can either pass the handler object itself or just its name and constructor parameters.

The handler class must have a handling method that is the first attribute in the JSON query after the handler name.

### Schema definition
Schemas in PeeQL are definitions of which columns can be used for retrieving data, filtering, sorting, etc.

Schemas in PeeQL are defined using `PeeQL\Schema\PeeQLSchema` class. This class has two methods for defining routes:
1. `PeeQL\Schema\PeeQLSchema::addObjectSchema(string $name, ACommonSchema $schema)`
    - $name is the schema name
        - As of `PeeQL v1.0` schema names are composed by the handler name (the first attribute after `definition` in the JSON query) and the handler method name (the first attribute after the handler name)
    - $schema is the schema class
        - The schema class must extend the `PeeQL\Schema\ACommonSchema` abstract class
2. `PeeQL\Schema\PeeQLSchema::addSchema(string $className, ?string $name = null)`
    - $className is the schema class name
        - No parameters are required
    - $name is the schema name
        - As of `PeeQL v1.0` schema names are composed by the handler name (the first attribute after `definition` in the JSON query) and the handler method name (the first attribute after the handler name)