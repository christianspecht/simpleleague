@echo off

call version-number.bat

del release\*.* /q

%~dp0\..\7za.exe a -tzip release\simpleleague-%versionnumber%.zip @releasefiles.txt

pause