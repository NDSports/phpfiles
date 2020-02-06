<?php
define('DB_NAME', 'hamstring_v5');
define('DB_USER', 'root');
//Server
define('DB_PASSWORD', 'Cd8734Ce6^%!');
//Localhost
//define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');

//BASIC AUTHENTICATIONS FOR SERVICES
define("AUTH_USERNAME","irishapps_hamstringv2");
define("AUTH_PASSWORD","!r!2Hap9s@#16@hamstringv2*");
define("AUTH_FAIL","You are not authorised to access this API.");

//Server
define("UPLOAD_PATH","/var/www/html/api/hamstring/images/");
define("MYSQL_BKUP_PATH","/var/www/htmlphp/api/hamstring/mysqldump/");
define("UPLOAD_SERVER_PATH","http://52.214.192.147/api/hamstring/images/");
define("REPORT_UPLOAD_PATH","/var/www/html/api/hamstring/reports/");
define("REPORT_UPLOAD_SERVER_PATH","http://52.214.192.147/api/hamstring/reports/");
define("IMAGES_PATH","http://52.214.192.147/api/hamstring/images/");
define("FILES_PATH","http://52.214.192.147/api/hamstring/images/");
define("JSON_PATH","http://52.214.192.147/api/hamstring/");
define("DB_PATH","api/hamstring/images/");
define("BASE_SERVER_URL", "http://52.214.192.147/");

//Localhost
//define("UPLOAD_PATH","./images/");
//define("UPLOAD_SERVER_PATH","localhost/Hamstring/images/");

// Error Messages
define("DB_CONNECTION_ERROR", "Error in DB Connection!");
define("WRONG_PARAM", "Something wrong with the parameters!");
define("CANNOT_BLANK", "Data cannot be blank!");
define("USER_NOTEXIST", "User account Deactivated or not exist!"); 
define("PASSWORD_EMPTY", "Password should not be empty!");
define("PASSWORD_INCORRECT", "Incorrect Password!");
define("EMAIL_INCORRECT", "Incorrect Email!");
define("MOBILE_INCORRECT", "Incorrect Mobile Number!");
define("EMAIL_EXIST", "E-Mail ID already exists!. Please try to login or try to reset your password.");
define("MOBILE_EXIST", "Mobile Number already exists!. Please try to login or try to reset your password.");


define("INSERT_SUCCESS", "New Record(s) has been created successfully.");
define("INSERT_FAILED", "New Record(s) failed to create.");
define("UPDATE_SUCCESS", "Record has been updated successfully.");
define("UPDATE_FAILED", "Record failed to update.");
define("DELETE_SUCCESS", "Record(s) has been deleted successfully.");
define("DELETE_FAILED", "Record(s) failed to delete.");
define("RECORD_NOT_EXISTS","No Record found!");
define("RECORD_DISABLED","Record has been disabled");
define("RECORD_REMOVED","Record has been removed");
define("INVALID_USER","Invalid user to provide details");

define("DEF_ALL_TABLE_REQUIRES",false);


define("EC_RECORD_NOT_EXISTS","10001");
define("EC_RECORD_DISABLED","10002");
define("EC_RECORD_REMOVED","10003");
define("EC_INVALID_USER","10004");
define("EC_RECORD_FAILED_TO_INSERT","10005");
define("EC_RECORD_FAILED_TO_UPDATE","10006");
define("EC_RECORD_FAILED_TO_DELETE","10007");


?>
