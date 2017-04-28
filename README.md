# Assets Bundle for Contao Open Source CMS

The assets bundle provides a simple asset versioning system for Contao Open Source CMS. The main idea
behind it is to append the current asset version to the file URL so it can be easily cached by the client
and re-downloaded when necessary.

Normally you would include the asset as follows:

```html
<link rel="stylesheet" href="layout.css">
```

The problem with this solution is that you can't really control the file cache. Either the client will
download it every time or cache it for some amount of time. However you are not able to reset that cache
when necessary, e.g. when the new version of the file is uploaded.

The solution is to use the versioning technique, also known as URL fingerprinting:

```html
<link rel="stylesheet" href="layout.css?v=e6aee573">
```

This ensures that every time the file changes, the client will download it again. It works well with
setting the cache control headers to the long expiration time as you can cache the assets even up to 1 year
which will boost the page load time quite significantly.


## How it works?

Every asset file specified in the configuration is parsed by the bundle extension which checks its content
and compiles the unique version hash. It is guaranteed that if the file content has not changed the hash
remains the same as previously.

The enhanced file data is then stored in the package registry which acts as a simple data container. 
You can fetch the package from it that is already enhanced with version data and include it on the page.


## Installation

Install the bundle using composer:

```
$ composer require terminal42/contao-assets
```

Then run the Contao Install Tool and update the database.


## Configuration

You can specify the multiple asset packages in the `app/config/config.yml` file as follows: 

```yml
terminal42_assets:
    root_dir: %kernel.root_dir%/../web
    collections:
        foobar:
            name: "Foobar"
            css:
                - foobar.css
                - { name: layout/foobaz.css }
            js:
                - foobar.js
                - { name: layout/foobaz.js, section: header } # section can be "header" or "footer"
```

Then for each page layout you can select an asset package that will be automatically added to the page.


## Static file caching

For the better cache control over the asset static files it is recommended to set the expiring headers as long
as possible. It is safe as whenever the file content changes it will have a new version URL parameter so the client
will automatically download the file again.

### Apache / LiteSpeed

Here is the example `.htaccess` file you can put in your assets folder:

```htaccess
<IfModule mod_expires.c>
  ExpiresActive on
  ExpiresByType text/css               "access 1 year"
  ExpiresByType application/javascript "access 1 year"
</IfModule>
```

That will set the `max-age` of the cache control header to 1 year for all CSS and JS files:

```
Cache-Control:max-age=31536000
```

**Warning:** it is recommended to put the above file in a subfolder that contains only the assets you want to cache.
You should probably not put it in the root directory as it will affect all the assets, including those that are not
supported by versioning system (e.g. for the Contao backend or included by custom extensions).

### nginx

Add the following to your Nginx server configuration:

```nginx
map $sent_http_content_type $expires {
  default                       off;
  text/css                      1y;
  application/javascript        1y;
}

location /layout {
  expires $expires;
}
```

**Note:** The location block above assumes that your assets are stored in the `web/layout` folder. Please adjust the
path to fit your needs.
