# contao-assets

## concept

Bundle configuration for CSS and JavaScript files.

```yml
terminal42_assets:
    root_dir: %kernel.root_dir%/../web/layout
    packages:
        foobar:
            name: "Foobar"
            css:
                - foobar.css
                - {name: foobaz.css}
            js:
                - foobar.js
                - {name: foobaz.js, section: header} # section can be "header" or "footer"
```

Or it could simply be *files* instead of *css* and *js* and determine by the file extension.
    
1. A layout configuration will be selectable in the Contao page layout (radio button or select menu).
2. A compiler pass should create hash-versions of each file (for browser cache reset)

## Static file caching

For the better cache control over the asset static files it is recommended to set the expiring headers as long
as possible. It is safe as whenever the file content changes it will have a new version URL parameter so the client
will automatically download the file again.

**Note that the below directives affect all CSS and JS files on the server!**

Example `.htaccess` directives:

```htaccess
<IfModule mod_expires.c>
  ExpiresActive on
  
  ExpiresByType text/css               "access 1 year"
  ExpiresByType application/javascript "access 1 year"
</IfModule>
```
