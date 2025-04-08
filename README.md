# PeeQL
PeeQL stands for PHP (or PeeHP) Query Language. It allows converting and mapping JSON queries to PHP classes for database querying.

This project took inspiration from GraphQL but it is rather more basic.

This project is currently work in progress.

## How does it work?
You import PeeQL to your project and initialize an instance of PeeQL (see `src/PeeQL.php`). From there you obtain a router and define routes to your repositories, models or other database operations handlers.

Then you can create your JSON queries (see _ADD_LINK_TO_THE_JSON_QUERY_TUTORIAL_SECTION_) and send them to the `PeeQL` class. `PeeQL` class will process the JSON query and return the result from the database.