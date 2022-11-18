<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Oscillate

Web based tool where user can upload the list (CSV File) and input which company heuser wants to track within the given date range.

- Providing maximum profit by buying and selling product between two date range
- Providing standard deviation, mean stock price and profit made by the user
- Relational database MySQL used to store the data.
- Admin panel created with responsive UI
- Vue JS is used to create the front end application

## Prerequisites
* PHP 7+
* Node JS
* npm

## Installing

Clone this project to your directory .

```shell
git clone git@github.com:itsmeJithin/oscillate.git
```

Open your terminal and change directory to this project directory

```shell
cd <Path to your directory>
```
To install php dependencies of this project, run the following command on your terminal

```shell
composer install && composer dump-autoload -o
```
#####Note: All database migration scripts added to the `migration` folder.

After completion of database migrations you should update the database credentials and database 
name in the following file
```phpt
oscillate/src/com/oscillate/core/service/BaseService.php
```
Replace with your credentials

```phpt
 $this->mySqlConnector->DB_HOST = "localhost";
 $this->mySqlConnector->DB_USER = "YOUR_USER_NAME";
 $this->mySqlConnector->DB_PASSWORD = "YOUR_PASSOWRD";
 $this->mySqlConnector->DB_NAME = "oscillate";
```

Run the following command to install vue application dependencies
```shell
cd ui
npm install
npm run serve
```
You can check the web application is running or not by checking below url on your web browser 
```html
localhost:8080
```

Before executing this application you should change the base URL value according to your virtual host name.
```javascript
oscillate/ui/main.js
axios.defaults.baseURL="http://localhost/" //<-- Replace with your virtual host;
```

####NOTE: 
If you are facing CORS issue in your web application, you can install the following 
[CORS unblock](https://chrome.google.com/webstore/detail/cors-unblock/lfhmikememgdcahcdlaciloancbhjino?hl=en) Chrome 
browser extension and enable it before starting  




