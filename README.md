# Modular_Laravel

[![Packgist version](https://img.shields.io/badge/dynamic/xml?label=packagist&query=%2F%2Fspan%5B%40class%3D%27version-number%27%5D%5B1%5D%2Ftext%28%29%5B1%5D&url=https%3A%2F%2Fpackagist.org%2Fpackages%2Frp76%2Fmodule)](https://packagist.org/packages/rp76/module)
[![GitHub license](https://img.shields.io/github/license/RezaParsian/Modular_Laravel)](https://github.com/RezaParsian/Modular_Laravel/blob/master/LICENSE)

`rp76/module` is a Laravel package that allows you to split your large project to pieces to managing them easier and use them many times to another project.

This package allows you to have many Routes, Models, Controllers, Migrations and Views without **conflict** on team project.
Also, each module has special **composer** for itself.

## Install

To install through Composer, by run the following command:
```
composer require rp76/module
```
You can add the package to your project by entering the following command
```
// config/app.php
'providers' => [
    ...
    \Rp76\Module\ModuleServiceProvider::class,
],
```
then publish the package's configuration file by running:
```
php artisan vendor:publish --tag=RpModule
```
or
```
php artisan vendor:publish --provider="Rp76\Module\ModuleServiceProvider"
```
or any way you know.

**Tip: new modules don't need any composer install or dump−autoload**

##Documentation
It's a simple package, we have few **command** to make new files.

### Start Module
```
php artisan module:install 
```
**This command makes modules folder in your root folder of project with template module.**

**<label style="color:green">It's necessary to use once.</label>**

### Make New Module
```
php artisan module:make {name} //php artisan module:make Book 
```
**Tip: You must <lable style='color:yellow;'>add</lable> your module name to <lable style='color:yellow;'>config/RpModule.php<lable>**

### Make New Controller
```
php artisan module:controller {name} {moduleName} --r //php artisan module:controller BookController Book 
```
**Tip:**
1. Some parameters are optional like **--r**.
2. **--r** parameter helps you to make **resource** controller.


### Make New Model
```
php artisan module:model {name} {moduleName} --m //php artisan module:model Book Book 
```
**Tip:**
1. Some parameters are optional like **--m**.
2. **--m** parameter helps you to make **migration** and model.

### Make New Migration
```
php artisan module:migration {name} {moduleName} //php artisan module:migration create_book Book 
```

##License
The MIT License (MIT). Please see [License File](https://github.com/RezaParsian/Modular_Laravel/blob/master/LICENSE) for more information.
