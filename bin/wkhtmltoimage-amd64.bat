@ECHO OFF
SET BIN_TARGET=%~dp0/../vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64
php "%BIN_TARGET%" %*
