Mall Of Turkey
--------------


Development Environment Setup
-----------------------------
The repo has a shell script `bin/init.sh`.
`init.sh` picks up a few environment variables. Following values are set through docker-compose.yml
```
      APP_PATH: /var/www/mot
      DB_HOST: mot-backend-db
      DB_NAME: mot
      TEST_DB_NAME: mottest
      TEST_DB_HOST: mot-test-db
```
The script depends on existence of separate DB instance and works well with current docker setup. In case dev are using non docker based dev environment, they will need to modify the variables mentioned above.
That would mean dev will have to change only their environment scripts instead of modifying a file. 

`init.sh` will execute unit tests. It is recommended that when developers checkout a branch or merge code from other branches, they should always execute unit tests.

Directory structure
-------------------
Actual laravel app sits in a directory `mot`. Most of the directories at that level are for docker setup, for example, `mysql` or `php`

Application is a laravel 8 application. It has three front facing components.

Administration Dashboard
-------------------------
 By default an admin is inserted with following credentials. Once deployed into production, this user should be disabled and a working user should be created.

U: info@mot.com
P: admin125

Admin Login : http://mall-of-turkey:9090/admin/login

Seller Dashboard
-----------------
By default a seller is inserted with following credentials. Once deployed into production, this user should be disabled and a working user should be created.

U: seller@mot.com
P: password

Seller Login : http://mall-of-turkey:9090/seller/login

We intend to change both dashboard and login endpoint to a separate subdomain.

Actors
------
There are three main types of actors in the app. each using a different table to store its information.
* Admin Currently uses table users, we intend to rename that table  at some point.
* Sellers Currently uses table vendors, we intend to rename that table entity to Seller at some point.
* Customers Currently uses table customers.
* Guest : A table is not used.

Laravel App has defined the following guards for above actors.

* web [for admin, we will need to rename it]
* customer 
* seller 
* guest

Only Seller should be required to verify the email address prior to continue. All other actors may continue without a verified account. [This is business logic and needs to be removed from doc once done.]

Customer Login
--------------

Different UserProvider classes are used to provider models for different actors. Customer login and register forms, in current design are on the same page. That brings in a complexity of showing error messages on specific forms, when the internals of each class has same fields name.

This will be handled by changing the field names on the form. That however has an effect of changing these names in UserProvider classess.


Service Architecture
--------------------

As mentioned in the Guideline, Controller methods generally call service classes.  The service classes are unit tested.
The use of service classes is demonstrated through unit tests.

Filter Product Service
----------------------
Any time an entity list is being generated, the controller should call, its service class or its filter service class to get that list.
Since a vast majority of the application has to deal with showing a list of products, a product filter service class is created.

The class internally keeps a laravel Query Builder object. user of this class may apply various filters as a chain.
Once application of such filter is done, users of the class call either of the following methods to generate the list of products.

* get
* paginate
* count

These methods call the respective methods on Query Builder. For the use of this Filter class , have a look at the unit tests.

Events
--------

Have a look at separate document about events.


Testing
-------

Structured as Unit testing and Feature testing. Unit testing is intended to test, various models and service classes. Feature testing makes a `GET` or `POST` call to the app to test if a certain feature is working.
Currently, Admin Feature tests are restricted to making only `GET` calls to ensure a url is available. We intend to do exhaustive feature testing for customers and sellers.
