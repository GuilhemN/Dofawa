#!/bin/bash

APPDIR="$(dirname "$(dirname "$0")")"
SHA224="$(sha224sum -t "$1" | head -c 56)"
TMPDIR="$APPDIR/tmp/build/memoclosure"

mkdir -p "$TMPDIR"

if [ -f "$TMPDIR/$SHA224.min.js" ]; then
	cat "$TMPDIR/$SHA224.min.js"
else
	/usr/bin/java -jar /var/lib/closure-compiler/compiler.jar --language_in ECMASCRIPT5 "$1" | tee "$TMPDIR/$SHA224.min.js"
fi
