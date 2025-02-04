![Screenshot](https://raw.githubusercontent.com/totyadev/totyadev-media-manager/master/arts/3x1io-tomato-media-manager.jpg)

# Filament media manager

[![Latest Stable Version](https://poser.pugx.org/totyadev/totyadev-media-manager/version.svg)](https://packagist.org/packages/totyadev/totyadev-media-manager)
[![License](https://poser.pugx.org/totyadev/totyadev-media-manager/license.svg)](https://packagist.org/packages/totyadev/totyadev-media-manager)
[![Downloads](https://poser.pugx.org/totyadev/totyadev-media-manager/d/total.svg)](https://packagist.org/packages/totyadev/totyadev-media-manager)

Manage your media files using spatie media library with easy to use GUI for FilamentPHP

## Installation

```bash
composer require totyadev/totyadev-media-manager
```

after install your package please run this command

```bash
php artisan totyadev-media-manager:install
```

finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`, if you like to use GUI and Folder Browser.

```php
->plugin(\TotyaDev\TotyaDevMediaManager\TotyaDevMediaManagerPlugin::make())
```

## Features

-   Manage your media files using spatie media library
-   Create folders and subfolders
-   Set password for folders
-   Upload Files with Custom Fields using `->schema()`
-   Auto Create Folders for Model/Collection/Record
-   RTL/Mutli Language Support

## Usage

you can use the media manager by add this code to your filament component

```php
use TotyaDev\TotyaDevMediaManager\Form\MediaManagerInput;

public function form(Form $form)
{
    return $form->schema([
        MediaManagerInput::make('images')
            ->disk('public')
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
            ]),
    ]);
}

```

## Add Custom Preview to selected type on the media manager

you can add custom preview to selected type on the media manager by add this code to your provider

```php
use TotyaDev\TotyaDevMediaManager\Facade\TotyaDevMediaManager;
use TotyaDev\TotyaDevMediaManager\Services\Contracts\MediaManagerType;


public function boot() {
     TotyaDevMediaManager::register([
        MediaManagerType::make('.pdf')
            ->icon('bxs-file-pdf')
            ->preview('media-manager.pdf'),
    ]);
}
```

on your view file you can use it like this

```php
<div class="m-4">
    <canvas id="the-canvas"></canvas>
</div>

<script src="//mozilla.github.io/pdf.js/build/pdf.mjs" type="module"></script>

<style type="text/css">
    #the-canvas {
        border: 1px solid black;
        direction: ltr;
    }
</style>
<script type="module">
    // If absolute URL from the remote server is provided, configure the CORS
    // header on that server.
    var url = "{{ $url }}";

    // Loaded via <script> tag, create shortcut to access PDF.js exports.
    var { pdfjsLib } = globalThis;

    // The workerSrc property shall be specified.
    pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.mjs';

    // Asynchronous download of PDF
    var loadingTask = pdfjsLib.getDocument(url);
    loadingTask.promise.then(function(pdf) {

        // Fetch the first page
        var pageNumber = 1;
        pdf.getPage(pageNumber).then(function(page) {
            var scale = 1;
            var viewport = page.getViewport({scale: scale});

            // Prepare canvas using PDF page dimensions
            var canvas = document.getElementById('the-canvas');
            var context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            // Render PDF page into canvas context
            var renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            var renderTask = page.render(renderContext);
        });
    }, function (reason) {
        // PDF loading error
        console.error(reason);
    });
</script>
```

you can attach global `js` or `css` file to the media manager by add this code to your provider

```php
use TotyaDev\TotyaDevMediaManager\Facade\TotyaDevMediaManager;
use TotyaDev\TotyaDevMediaManager\Services\Contracts\MediaManagerType;


public function boot() {
     TotyaDevMediaManager::register([
        MediaManagerType::make('.pdf')
            ->js('https://mozilla.github.io/pdf.js/build/pdf.mjs'),
            ->css('https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.3.136/pdf_viewer.min.css'),
            ->icon('bxs-file-pdf')
            ->preview('media-manager.pdf'),
    ]);
}
```

please note that the `name ` of the component will be the same name of the collection.

## Allow Sub Folders

you can allow create and manage subfolders on your media manager on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(
    \TotyaDev\TotyaDevMediaManager\TotyaDevMediaManagerPlugin::make()
        ->allowSubFolders()
)
```

## Allow User Access

now you can allow user to access selected folder and restract user to access each other folders if the folder is not public on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(
    \TotyaDev\TotyaDevMediaManager\TotyaDevMediaManagerPlugin::make()
        ->allowUserAccess()
)
```

now on your user model you can use this trait to allow user to access selected folder

```php

use TotyaDev\TotyaDevMediaManager\Traits\InteractsWithMediaFolders;

class User extends Authenticatable
{
    use InteractsWithMediaFolders;
}
```

**NOTE** don't forget to migrate after update the plugin

## Folders API

now you can access your media and folders using API you have 2 endpoints

-   `/api/folders` to get all folders
-   `/api/folders/{id}` to get folder by id with sub folders and media files

to allow this feature you need to publish the config file by use this command

```bash
php artisan vendor:publish --tag="totyadev-media-manager-config"
```

then you can set `api.active` to `true` on the config file

```php
'api' => [
    "active" => true,
],
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="totyadev-media-manager-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="totyadev-media-manager-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="totyadev-media-manager-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="totyadev-media-manager-migrations"
```

## Other Filament Packages

Checkout our [Awesome TotyaDev](https://github.com/tomatophp/awesome)
