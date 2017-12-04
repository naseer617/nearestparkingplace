Technical Details
-----------------
* Ubuntu 16.04 64-bit
* Apache 2.4
* PHP 5.6
* MySQL 5.6
* XDebug
* PHPUnit 4.8
* Codeception 2.1
* Composer
* Bower
* Yii2
* google maps api

Pre-Reqs
--------
* Vagrant 1.8.5
* Virtualbox 5.*

Installation
------------
Run following commands on terminal

* vagrant up
This command will install ubuntu machine, will take from 2-3 mins

* vagrant ssh
Logs you in the machine

* cd /var/www/parkman

* sh install.sh
This command will install codeception globally, yii2 packages, jquery and bootstrap, initialise DB and Insert dummy data in DB
 
Running Unit Tests
-------------------
* cd /var/www/parkman/tests
* codecept run 
    
## MySQL
Externally the MySQL server is available at port 8889, and when running on the VM it is available as a socket or at port 3306 as usual.
Username: root
Password: root

##Access URLS
* http://parkman.local

##Screen Shots

* ### ** Main View** ###

![Screen Shot 2017-02-09 at 10.54.12.png](https://bitbucket.org/repo/k87BRy/images/2157835968-Screen%20Shot%202017-02-09%20at%2010.54.12.png)


* ### **Search By Country** ###

![Screen Shot 2017-02-09 at 10.54.23.png](https://bitbucket.org/repo/k87BRy/images/673234631-Screen%20Shot%202017-02-09%20at%2010.54.23.png)

* ### **Search By Latitude & Longitude** ###

![Screen Shot 2017-02-09 at 10.54.42.png](https://bitbucket.org/repo/k87BRy/images/3011761584-Screen%20Shot%202017-02-09%20at%2010.54.42.png)

* ### **Search By Owner** ###

![Screen Shot 2017-02-09 at 10.54.50.png](https://bitbucket.org/repo/k87BRy/images/2384509227-Screen%20Shot%202017-02-09%20at%2010.54.50.png)

**NOTE : proximity is the circular radius in KM**

##Resource URLS
* https://github.com/2amigos/yii2-google-maps-library

* http://www.generatedata.com/

* http://stackoverflow.com/questions/4687312/querying-within-longitude-and-latitude-in-mysql/4690450#4690450

* http://stackoverflow.com/questions/365826/calculate-distance-between-2-gps-coordinates

* https://developers.google.com/maps/articles/phpsqlsearch_v3