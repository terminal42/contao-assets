# contao-assets

## concept

Bundle configuration for CSS and JavaScript files.

```yml
    terminal42_assets:
        root: "%kernel.project_dir%/web/layout"
        layouts:
            foobar:
                name: "Foobar"
                header:
                    - foobar.css
                    - jquery.js
                footer:
                    - foobar.js
```

Other ideas:

```yml
    terminal42_assets:
        root: "%kernel.project_dir%/web/layout"
        layouts:
            foobar:
                name: "Foobar"
                css:
                    - foobar.css
                js:
                    - jquery.js
                    - { name: foobar.js, position: footer }
```

Or it could simply be *files* instead of *css* and *js* and determine by the file extension.
    
1. A layout configuration will be selectable in the Contao page layout (radio button or select menu).
2. A compiler pass should create hash-versions of each file (for browser cache reset)
