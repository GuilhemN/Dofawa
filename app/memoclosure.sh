#!/bin/bash

APPDIR="$(dirname "$(dirname "$0")")"
MD5="$(md5 -t "$1" | head -c 56)"
TMPDIR="$APPDIR/tmp/build/memoclosure"

mkdir -p "$TMPDIR"

if [ -f "$TMPDIR/$MD5.min.js" ]; then
	cat "$TMPDIR/$MD5.min.js"
else
	/usr/bin/java -jar /var/lib/closure-compiler/compiler.jar --language_in ECMASCRIPT5 "$1" | tee "$TMPDIR/$MD5.min.js"
fi
