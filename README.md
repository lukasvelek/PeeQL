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