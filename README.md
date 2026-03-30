# House Review Dashboard

This is a website to help you review houses by title, rent and energy
label. The website reads in a JSON file containing house data, grades
these houses and creates a dashboard where the user can review
rentable properties by the grades and information and approve or reject the
house appropriately.

## Setup

In order to use this application, you will need to install [PHP](https://www.php.net/downloads.php),
[Composer](https://getcomposer.org/download/) and [Symfony CLI](https://symfony.com/download).

### Instructions

Clone the repository, and then install the dependencies and initialize the Symfony framework and
database. Also compile Tailwind CSS to make the frontend work. You can then also start the server.

    # Clone the repository
    $ git clone git@github.com:Nanolento/housereview.git
    $ cd housereview

    # Install the dependencies
    $ composer install

    # Initialize the database
    $ php bin/console doctrine:migrations:migrate

    # Compile Tailwind CSS
    $ php bin/console tailwind:build

    # Start the server
    $ symfony server:start

> [!WARNING]
> You might need to change your PHP memory limit if it is at the
> default 128M setting. I noticed the `tailwind:build` step would fail
> with an _OutOfMemoryError_ and to fix this, increase the PHP memory
> limit by editing your `php.ini`.

To load in the house data, please copy your JSON file to the `data/`
directory inside the project directory. Please create the directory if
it does not exist. Adjust the `$filePath` inside
`config/services.yaml` to the filename of your JSON data.

## Usage

You can view the dashboard by going to
[http://127.0.0.1:8000/](http://127.0.0.1:8000/) when the server is running.

> [!NOTE]
> The dashboard should automatically load the houses from the database
> if it can't find any. Are there still no houses? Please try visiting
> the [/load](http://127.0.0.1:8000/load) endpoint to manually load
> them. If it still does not work, please check your configuration.

## Documentation

### Overview

This website uses the Symfony PHP framework and its Twig
templating. For the database we use SQLite. Symfony's Doctrine
abstracts away the database handling so we do not have to worry about
SQL ourselves, except for reviewing migrations. We also make use of
Tailwind CSS on the front-end to get a nice and professional
interface.

### General flow

When you load the website, the DashboardController will ask Twig to
render the `dashboard.html.twig` template. This template will render
and includes the `HouseContainer` live component. This component will then
load the houses, apply the status filter if set and create
`HouseListing` component for each house. The `HouseListing` live
component will present the houses to the user in an easy-to-understand
way and handle approving/rejecting the houses, including saving that
status to the database.

### Files

These are the code files in `src/` and what they do:

- `Controller/DashboardController.php`
  - main controller file that handles routing and such.
- `Service/HouseLoader.php`
  - handles parsing the JSON data and loading this data into the
    SQLite database, automatically called by the house container if it
    can't find any houses.
- `Twig/Components/HouseListing.php`
  - data model for the house listing UX component.
  - This Live Component handles showing the houses and
    approving/rejecting houses by the user.
- `Twig/Components/HouseContainer.php`
  - data model for the house container UX component.
  - This Live Component handles filtering the houses by status.
- `Entity/House.php`
  - ORM data model for the House object, providing get and set
    methods.

Then we have the templates:

- `dashboard.html.twig`
  - Dashboard template, contains the main UI
- `components/HouseListing.html.twig`
  - Template for rendering the house listing UX component.
- `components/HouseContainer.html.twig`
  - Template for rendering the house container. This template also
    pulls in the houses from the database.

Miscellaneous files:

- `assets/styles/app.css`
  - contains the Tailwind CSS styling for our own classes for elements
    that appear more than once in the template.
- `config/services.yaml`
  - adjust the file path to your JSON input data here.

## Notice

This website was made as a test assignment for a job application. I
learned a lot about Symfony, Symfony UX, UX Live Components, Tailwind
CSS and working with modern tools in this project. I found it really
pleasant to work with these tools, especially Symfony and Tailwind CSS
were a breeze.