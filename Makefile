TRACEUR = /usr/local/bin/traceur
CLOSURE = /usr/bin/java -jar /var/lib/closure-compiler/compiler.jar
LESSC = /usr/local/bin/lessc
CSSMIN = /usr/local/bin/cssmin
GZIP = /bin/gzip
RM = /bin/rm
LN = /bin/ln
SED = /bin/sed
TRUE = /bin/true

TRFLAGS =
CLFLAGS = --language_in ECMASCRIPT5
LCFLAGS =
CMFLAGS =
GZFLAGS = -9 -n
RMFLAGS = -f
LNFLAGS = -f -s

# Our LESS input file(s)
LESS = $(shell find src -type f -name '*.less')

# Our Harmony/ES6 input file(s)
ES6 = $(shell find src -type f -name '*.es6')

# Our miscellaneous input files (fonts, icons, ...)
MISC =

# Our CSS list (replaces .less with .css in the list)
CSS = $(LESS:.less=.css)

# Our JS list (replaces .es6 with .js in the list)
JS = $(ES6:.es6=.js)

# Our pre-existing or already generated non-minified file(s)
CSS_PRE = $(shell find src -type f -name '*.css' ! -name '*.min.css')
JS_PRE = $(shell find src -type f -name '*.js' ! -name '*.min.js')

# Our minified file list
CSS_MIN = $(CSS:.css=.min.css) $(CSS_PRE:.css=.min.css)
JS_MIN = $(JS:.js=.min.js) $(JS_PRE:.js=.min.js)

# Our pre-existing or already generated minified file(s)
CSS_PMN = $(shell find src -type f -name '*.min.css')
JS_PMN = $(shell find src -type f -name '*.min.js')

# Our compressed file list
JS_GZ=$(JS_MIN:=.gz) $(JS_PMN:=.gz)
CSS_GZ=$(CSS_MIN:=.gz) $(CSS_PMN:=.gz)
MISC_GZ=$(MISC:=.gz)

# This is our default target, so simply typing `make` will run this (dependency is the `dist` target)
all: comp

# Translate from .less to .css using the following command ($< = input file, $@ = output file)
%.css: %.less
	$(LESSC) $(LCFLAGS) $< > $@

# The same as above, bith with minification
%.min.css: %.css
	$(CSSMIN) $(CMFLAGS) $< > $@
	$(SED) -i -e 's/calc(\([^)+-]\+\)\([+-]\)\([^)]\+\))/calc(\1 \2 \3)/g' $@

# Translate from .es6 to .js
%.js: %.es6
	$(eval LINK := $(shell mktemp /tmp/tmp.XXXXXX.js))
	$(LN) $(LNFLAGS) /dev/stdin $(LINK)
	$(TRACEUR) $(TRFLAGS) --out $@ --script $(LINK) < $< || $(TRUE)
	$(RM) $(RMFLAGS) $(LINK)

# And now for our uglifying our JavaScripts
%.min.js: %.js
	$(CLOSURE) $(CLFLAGS) $< > $@

# Pre-compression
%.gz: %
	$(GZIP) $(GZFLAGS) -c $< > $@

# This target simply creates distribution versions of our JavaScript and CSS files
dist: js-dist css-dist

comp: js-comp css-comp misc-comp

css: $(CSS)

js: $(JS)

css-dist: $(CSS_MIN)

js-dist: $(JS_MIN)

css-comp: $(CSS_GZ)

js-comp: $(JS_GZ)

misc-comp: $(MISC_GZ)

# Clean up everything we can possibly have made
clean:
	$(RM) $(RMFLAGS) $(CSS) $(JS) $(CSS_MIN) $(JS_MIN) $(CSS_GZ) $(JS_GZ)

# These can never be valid targets (because we have folders with these names)
.PHONY: clean all dist comp js css js-dist css-dist css-comp js-comp misc-comp
