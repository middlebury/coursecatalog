The Course-Catalog is a web front-end for searching and browsing course information stored in Ellucian Banner.


# Overview of operation

1. (Nightly) Data from the Banner Oracle database is copied into tables in a MySQL database (via [`coursecatalog/bin/update-from-banner.php`](https://github.com/middlebury/coursecatalog/blob/master/bin/update-from-banner.php))
2. (Nightly) Derived tables and views to improve the ease of fetching are built from the data now in MySQL (via [`coursecatalog/bin/update-from-banner.php`](https://github.com/middlebury/coursecatalog/blob/master/bin/update-from-banner.php))
3. (Nightly) Search indices are built based on the data now in MySQL (via [`coursecatalog/bin/build_indices.php`](https://github.com/middlebury/coursecatalog/blob/master/bin/build_indices.php))
4. The PHP data model based on the OSID Course Catalog API provides an object-oriented API for accessing the course catalog data. This API ensures consistency in data fetching so that different user-interface screens always have the same information available.<
5. The front-end application (using the Zend Framework's MVC system) provides search, browse and display interfaces to access the course information. It also provides XML web services for using the course information in remote systems. Additionally, the front-end application includes a schedule-planning tool to help students plan their semesters. All user-interfaces and web services get their data through the OSID Course Catalog API.

# Examples

Examples of the the Course-Catalog in action at <a href="http://www.middlebury.edu">Middlebury College</a>:

* [Main Search UI](https://catalog.middlebury.edu/catalogs/view/catalog/catalog-MCUG) (catalog app)
* [Section Details Page](https://catalog.middlebury.edu/offerings/view/catalog/catalog.MCUG/offering/section-201090-91241) (catalog app)
* [Department "courses" RSS feed](https://catalog.middlebury.edu/courses/topicxml/catalog/catalog.MCUG/topic/topic-department-BIOL) (catalog app)
* [Departments listing to feed to the Drupal content type form](https://catalog.middlebury.edu/topics/listdepartmentstxt/catalog/catalog-MCUG/) (catalog app)
* [Department Course Listing](http://www.middlebury.edu/academics/bio/courses) (Drupal "courses" content-type displaying a feed from the catalog app)
* [Department Section Listing](http://www.middlebury.edu/academics/bio/courses/offerings) (Drupal "courses" content-type displaying a feed from the catalog app)
* [Faculty Profile](http://www.middlebury.edu/academics/bio/faculty/node/48111) (Drupal "profile" content-type displaying a feed from the catalog app)</li>
</ul>

[Schedule Planner](https://github.com/middlebury/coursecatalog/wiki/Schedule-planner) - Screen-shots and more information about the schedule-planner.

# Installation

These instructions assume that you have a POSIX machine running Apache with PHP 7.0 or later.

1. In a non-web-accessable directory, clone the course-catalog repository:
   ```git-clone https://github.com/middlebury/coursecatalog.git```

2. cd into the new `coursecatalog/` directory:
   ```
   cd coursecatalog
   ```
3. Install dependencies with Composer:
   ```
   composer install
   ```
4. Make a symbolic link to the `coursecatalog/docroot/` directory in a web-accessible directory or add a virtualhost rooted in the `coursecatalog/docroot/` directory.
5. Create a MySQL database for the catalogs data and a cache of Banner data.
6. Make a `.env.local` file and add database configuration details at a minimum.
7. Create the database tables defined in `application/library/banner/course/sql/table_creation.sql`
8. Run the script at `bin/update-from-banner.php` to dump Banner data into the the MySQL database:
   ```
   php bin/update-from-banner.php
   php bin/build_indices.php
   ```

## Development environment setup

### Lando
Install Docker and Lando to provide a local containerized environment

In the code directory, start the local containers with `lando start`

### Copying the production database
Dump the production database to a non version-controlled file path like `var/catalog_prod.sql`.

Strip out any `DEFINER` statements that will break the import:
```
sed -i '' 's/DEFINER=[^*]*\*/\*/g' var/catalog_prod.sql
```

Import the database into the local container:
```
lando db-import var/catalog_prod.sql
```

### Scheduled jobs and worker processes

#### Database update jobs



#### Archive export jobs.

Exports for all active jobs can be enqueued for export by a messenger consumer
worker (see below) with:
```
lando ssh -c "bin/console app:export:enqueue:active"
```

Similarly, a single export jobs can be triggered with:
```
lando ssh -c "bin/console app:export:enqueue:single <job-id>"
```

Alternatively there are two commands that can directly export the print catalogs
without relying on the messenger worker process:

```
lando ssh -c "bin/console app:export:active"
```

```
lando ssh -c "bin/console app:export:single <job-id>"
```

#### Messenger consumer worker

The Archive exporter uses the Symfony messenger system to let the admin UI
queue up builds of the archive exports which are then processed asynchronously
by the `messenger:consumer` worker process. In production a `messenger:consumer`
process should always be kept running either via
[supervisor](https://symfony.com/doc/current/messenger.html#supervisor-configuration),
[systemd](https://symfony.com/doc/current/messenger.html#systemd-configuration), or via a
[docker command](https://github.com/dunglas/symfony-docker/issues/539#issuecomment-2345964974)
depending on the deployment environment.

In development, the worker process can be manually started with:
```
lando ssh -c "bin/console messenger:consume -vv"
```

### Unit Tests

Most of the Course Catalog OSID API is covered by PHPUnit tests. To run these tests:

1. Install PHPUnit if you do not have it available.
2. Create an empty MySQL database for running the tests.
3. Edit `.env.test` and enter your database configuration parameters.
4. On the command-line, change directory to your course-catalog source directory.
5. Run the command `phpunit` from within the application directory.
   If you are using lando for your development environment, run
   `lando ssh -c "phpunit"`

### Code style checks
Use `php-cs-fixer` to check coding style with:

    lando ssh -c "vendor/bin/php-cs-fixer check"

`php-cs-fixer` can automatically make some changes with:

    lando ssh -c "vendor/bin/php-cs-fixer fix"

# Configuration

To configure the application, create a `.env.local` file and copy
the sections you need to modify from `.env`.

## `.env`/`.env.local` changes

### Database
At a minimum, you will likely need to configure the `DATABASE_URL` to point at
your application database as well as repeating that config in `DATABASE_HOST`,
`DATABASE_DATABASE`, `DATABASE_USERNAME`, and `DATABASE_PASSWORD`.

### Saml Authentication
If you choose to use authentication, you will need to update these values to fit
your identity provider.

```
SAML_IDP_ENTITYID="https://idp.example.edu/abc123"
SAML_IDP_SINGLESIGNONSERVICE="https://idp.example.edu/abc123/saml2"
SAML_IDP_SINGLELOGOUTSERVICE="https://idp.example.edu/abc123/saml2"
SAML_IDP_X509CERT="MIIC...."
```

### Course Manager implementation
 - `banner_course_CourseManager`: The default implementation that queries a local
    copy of a Banner database to fetch data.
 - `apc_course_CourseManager`: An implementation that wraps APCU caching of
   commonly fetched results around the underlying implemenation. By default the
   `apc_course_CourseManager` uses the `banner_course_CourseManager` as its
   underlying implementation, but this can be configured to a custom one in
   `SYMFONYCACHE_BACKING_COURSE_MANAGER_IMPL`


# Implementation Notes

The implementation of this system is layered such that the Web UI code is separated from the data model. The data model is an implementation of the [Open Knowledge Initiative](http://www.okiproject.org/) (O.K.I.) Open Service Interface Definition (OSID) for Course information, the [Course OSID](http://en.wikipedia.org/wiki/CourseManagement_Open_Service_Interface_Definition) ([detailed doc](http://sourceforge.net/project/downloading.php?group_id=69345&amp;filename=OSID_CourseMgmt_rel_2_0.pdf&amp;40157442)). Because of this structure, it is possible for other institutions to modify the data model (the OSID implementation) so as to use the same UI code against different data sources, be they different Banner implementations or alternative systems. See also [OSID Usage](https://github.com/middlebury/coursecatalog/wiki/OSID-Usage).

# Change Log

## 2.1.0
- Remove support for the apc_course_CourseManager

## 2.0.1
- Added support for application and data-model caching via the Symfony Cache component
  which supports Memcached, Redis, and other cache back-ends in addition to APCu.

## 2.0.0
- Completely rewritten application code based on Symfony 6.4.
- Installation via composer
- New theme and UI.

## 1.1
Last release based on the Zend Framework.
