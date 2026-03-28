# House Review Dashboard

This is a website to help you review houses by title, rent and energy
label. The website reads in a JSON file and creates a dashboard where
the user can review rentable properties and make adjustments if
needed.

## Setup

In order to use this application, you will need to install [PHP](https://www.php.net/downloads.php),
[Composer](https://getcomposer.org/download/) and [Symfony CLI](https://symfony.com/download).

### Instructions

Clone the repository, and then install the dependencies and initialize the Symfony framework and
database:

    # Clone the repository
    $ git clone git@github.com:Nanolento/housereview.git
    $ cd housereview

    # Install the dependencies
    $ composer install

    # Initialize the database
    $ php bin/console doctrine:migrations:migrate

    # Start the server
    $ symfony server:start

To load in the house data, please copy your JSON file to the `data/`
directory inside the project directory. Please create the directory if
it does not exist. Adjust the `$filePath` inside
`config/services.yaml` to the filename of your JSON data.

## Usage

You can view the dashboard at the [root of the
website](http://127.0.0.1:8000/).

> [!WARNING]
> **Dev Note**: The website is currently in development. To load the
> house data into the database so the dashboard can use them, visit
> the [/load](http://127.0.0.1:8000/load) endpoint which will parse
> the JSON file. I hope to, in the future, make the dashboard do this
> automatically if it cannot find any houses.

## Documentation

This website uses the Symfony PHP framework and its Twig
templating. For the database we use SQLite. Symfony's Doctrine
abstracts away the database handling so we do not have to worry about
SQL ourselves, except for reviewing migrations.

These are the code files in `src/` and what they do:

- `Controller/DashboardController.php`
  - main controller file that handles routing and such.
- `Service/HouseLoader.php`
  - handles parsing the JSON data and loading this data into the
    SQLite database.
- `Twig/Components/HouseListing.php`
  - data model for the house listing UX component.
- `Entity/House.php`
  - ORM data model for the House object, providing get and set
    methods.

Then we have the templates:

- `dashboard.html.twig`
  - Dashboard template, contains the main UI
- `components/HouseListing.html.twig`
  - Template for rendering the house listing UX component.

## Notice

This website was made as a test assignment for a job application. I
learned a lot about Symfony and working with modern tools in this
project.