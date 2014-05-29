CSSMIN = /usr/local/bin/cssmin
LESSC = /usr/local/bin/lessc

# Our LESS input file(s)
LESS = $(shell find web -type f -name '*.less')

# Our CSS list (replaces .less with .css in the list)
CSS = $(LESS:.less=.css) 

# Our minified CSS list
CSS_MIN = $(CSS:.css=.min.css)

# Our JavaScript targets (head.js goes in <head> and body.js goes before </body>)
JS = $(shell find . -type f -name '*.src.js')

# Our minified JavaScript list
JS_MIN = $(JS:src.js=.min.js)

# Translate from .less to .css using the following command ($< = input file, $@ = output file)
%.css: %.less
	$(LESSC) $< > $@

# The same as above, bith with minification using the YUI CSS Compressor
%.min.css: %.css
	$(CSSMIN) $< > $@

# And now for our uglifying our JavaScripts
%.min.js: %.js
	$(UGLIFYJS) $< > $@

# This is our default target, so simply typing `make` will run this (dependency is the `dist` target)
all: clean assets dist

assets: 
	php app/console assets:install

# This target simply creates distribution versions of our JavaScript and CSS files
dist: js-dist css-dist

# Here's the amazing part, this variable resolves to any outstanding less -> css conversions depending 
# on when each .less file was last modified
css: $(CSS)

# The same as above, except we clean up the generated combined CSS files after minifying
css-dist: $(CSS_MIN)

js: $(JS)
js-dist: $(JS_MIN)
	rm -f $(JS)

# Clean up everything we can possibly have made
clean:
	rm -f $(CSS) $(CSS_MIN) $(JS) $(JS_MIN)

# These can never be valid targets (because we have folders with these names)
.PHONY: js css assets
