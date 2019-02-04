@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../leocavalcante/ippo/ippo
php "%BIN_TARGET%" %*
