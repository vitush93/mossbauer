# Frontend

My grunt Handlebars + Browserify scaffolding.

## Installation and configuration

Run `npm install` to download necessary modules. This will also run `bower install` automatically after all node modules are installed.

## Grunt

Running `grunt` does following for you:

- compiles sass, scripts and Handlebars templates
- starts watch task

To get your production assets run `grunt release` which:

- compiles and compress css and scripts

To use compiled stylesheets and scripts include **css/master.css** and **js/master.js** in your html files.

## Usage

### JavaScript

In this environment, you can write your scripts in node.js modular fashion. Browserify will then assemble your application into single file: `js/master.js`. `src/js/main.js` is configured as dependency root for your scripts.

To use external scripts with browserify (such as bower_components/ scripts or manually downloaded plugins) without specifying their full path in `require()` function, add their aliases to ``package.json:browser`` section.

### Handlebars Templates

This project comes with precompiled Handlebars templates. All template files in `src/templates` are compiled into `src/js/templates.js` and can be used as a node module. See `src/js/main.js` for example usage.

### Stylesheets

This project uses SASS as CSS preprocessor. Import all your stylesheets in `src/scss/main.scss`.

