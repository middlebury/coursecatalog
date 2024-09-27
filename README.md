The Course-Catalog is a web front-end for searching and browsing course information stored in Ellucian Banner.


Overview of operation
=====================

1. (Nightly) Data from the Banner Oracle database is copied into tables in a MySQL database (via [`coursecatalog/bin/update-from-banner.php`](https://github.com/middlebury/coursecatalog/blob/master/bin/update-from-banner.php))
2. (Nightly) Derived tables and views to improve the ease of fetching are built from the data now in MySQL (via [`coursecatalog/bin/update-from-banner.php`](https://github.com/middlebury/coursecatalog/blob/master/bin/update-from-banner.php))
3. (Nightly) Search indices are built based on the data now in MySQL (via [`coursecatalog/bin/build_indices.php`](https://github.com/middlebury/coursecatalog/blob/master/bin/build_indices.php))
4. The PHP data model based on the OSID Course Catalog API provides an object-oriented API for accessing the course catalog data. This API ensures consistency in data fetching so that different user-interface screens always have the same information available.<
5. The front-end application (using the Zend Framework's MVC system) provides search, browse and display interfaces to access the course information. It also provides XML web services for using the course information in remote systems. Additionally, the front-end application includes a schedule-planning tool to help students plan their semesters. All user-interfaces and web services get their data through the OSID Course Catalog API.

Examples
========

Examples of the the Course-Catalog in action at <a href="http://www.middlebury.edu">Middlebury College</a>:

* [Main Search UI](https://catalog.middlebury.edu/catalogs/view/catalog/catalog.MCUG) (catalog app)
* [Section Details Page](https://catalog.middlebury.edu/offerings/view/catalog/catalog.MCUG/offering/section.201090.91241) (catalog app)
* [Department "courses" RSS feed](https://catalog.middlebury.edu/courses/topicxml/catalog/catalog.MCUG/topic/topic.department.BIOL) (catalog app)
* [Departments listing to feed to the Drupal content type form](https://catalog.middlebury.edu/topics/listdepartmentstxt/catalog/catalog.MCUG/) (catalog app)
* [Department Course Listing](http://www.middlebury.edu/academics/bio/courses) (Drupal "courses" content-type displaying a feed from the catalog app)
* [Department Section Listing](http://www.middlebury.edu/academics/bio/courses/offerings) (Drupal "courses" content-type displaying a feed from the catalog app)
* [Faculty Profile](http://www.middlebury.edu/academics/bio/faculty/node/48111) (Drupal "profile" content-type displaying a feed from the catalog app)</li>
</ul>

[Schedule Planner](https://github.com/middlebury/coursecatalog/wiki/Schedule-planner) - Screen-shots and more information about the schedule-planner.

Installation
============

These instructions assume that you have a POSIX machine running Apache with PHP 7.0 or later.

1. In a non-web-accessable directory, clone the course-catalog repository:
   ```git-clone https://github.com/middlebury/coursecatalog.git```

2. cd into the new `coursecatalog/`directory and fetch the submodules (osid-phpkit, ZendFramework, etc):
   ```
   cd coursecatalog
   git-submodule update --init --recursive
   ```
3. Install additional dependencies with Composer:
   ```
   composer install
   ```
4. Make a symbolic link to the `coursecatalog/docroot/` directory in a web-accessible directory or add a virtualhost rooted in the `coursecatalog/docroot/` directory.
5. Create a MySQL database for the catalogs data and a cache of Banner data.
6. Make copies of the example config files at `configuration.plist`, `frontend_config.ini`, and `update_config.ini` and edit values to match your environment.
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

Unit Tests
----------

Most of the Course Catalog OSID API is covered by PHPUnit tests. To run these tests:

1. Install PHPUnit if you do not have it available.
2. Create an empty MySQL database for running the tests.
3. Edit `application/test/banner/configuration.plist` and enter your database configuration parameters.
4. On the command-line, change directory to your course-catalog source directory.
5. Run the command `phpunit application/test/TestSuite.php`


Implementation Notes
====================

The implementation of this system is layered such that the Web UI code is separated from the data model. The data model is an implementation of the [Open Knowledge Initiative](http://www.okiproject.org/) (O.K.I.) Open Service Interface Definition (OSID) for Course information, the [Course OSID](http://en.wikipedia.org/wiki/CourseManagement_Open_Service_Interface_Definition) ([detailed doc](http://sourceforge.net/project/downloading.php?group_id=69345&amp;filename=OSID_CourseMgmt_rel_2_0.pdf&amp;40157442)). Because of this structure, it is possible for other institutions to modify the data model (the OSID implementation) so as to use the same UI code against different data sources, be they different Banner implementations or alternative systems. See also [OSID Usage](https://github.com/middlebury/coursecatalog/wiki/OSID-Usage).
