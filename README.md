While asset001 was for import/export with AwesomeMiner (failed), asset002 is for direct manipulation of AwesomeMiner's XML file in %appdata%.  

Dependencies:  
https://developers.google.com/sheets/api/quickstart/php  
https://getcomposer.org/download/  
PHP 5.4+ installed locally with path variable set. The easy button here is the Bitnami LEMP or LAMP stack  
install for Windows. Then just set your path variable to where php.exe is.  

Ready for testing in production:  
0.) Make an export file of miners in case you derp  
1.) Only use when AwesomeMiner is completely closed !!  
2.) Place repository so that it is a subfolder in %appdata% AwesomeMiner folder. Index.php will  
look up one directory for "ConfigData.xml".  
3.) navigate to the same directory that index.php is in, in cmd.exe  
4.) In command prompt: "php index.php", press enter  
5.) After having closed AwesomeMiner to do said steps, now re-open AwesomeMiner. There should be a high  degree  98% plus of changes. This is due to irregularities not yet accounted for.  
  
ConfigData.xml is overwritten with a backup of original generated.