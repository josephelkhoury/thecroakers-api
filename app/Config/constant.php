<?php

define('API_KEY', '156c4675-9608-4591-1111-00000');
define('ADMIN_API_KEY', '156c4675-9608-4591-1111-00000');

date_default_timezone_set('Asia/Beirut');
define('BASE_URL', 'https://app.thecroakers.com/');
define('APP_STATUS', 'live');
define('APP_NAME', 'The Croakers');

define('ADMIN_RECORDS_PER_PAGE',20);
define('APP_RECORDS_PER_PAGE',20);

define('DATABASE_HOST', 'localhost');
define('DATABASE_USER', 'root');
define('DATABASE_PASSWORD', 'sX2cX8bW9cU9cT4z');
define('DATABASE_NAME', 'thecroakers');

define('UPLOADS_FOLDER_URI', 'app/webroot/uploads');
define('TEMP_UPLOADS_FOLDER_URI', 'app/webroot/temp_uploads');
define('FONT_FOLDER_URI', 'app/webroot/font');

define('WATERMARK_IMAGE_URI', 'app/webroot/img/watermark.png'); //the size should be 100x30

define('IMAGE_THUMB_SIZE', '150');
define('FIREBASE_PUSH_NOTIFICATION_KEY', 'AAAAEiN5CUU:APA91bETkYAZtGDyJDztgGt527u_UBhDqC120lhDvx0UJPvvxgFSkOg4R8tAv6cErJFpf-cpBhekA3XbusIQ4DtyHcefp8OAtSZZrFqQSgOaMgJ5ReE_1OV3X3Kq3Ol8XqXAvhh4kCiu');

//Twilio
define('TWILIO_ACCOUNTSID', 'Account SID Here');
define('TWILIO_AUTHTOKEN', 'Auth Token Here');
define('TWILIO_NUMBER', 'Number Here');
define('VERIFICATION_PHONENO_MESSAGE', 'Your verification code is');

//Facebook
define('FACEBOOK_APP_ID', 'Facebook App ID Here');
define('FACEBOOK_APP_SECRET', 'App Secret Here');
define('FACEBOOK_GRAPH_VERSION', 'v2.10');

//Google
define('GOOGLE_CLIENT_ID', '77904546117-90na2pptinsrpdhe3q3q705l4jdtdbbv.apps.googleusercontent.com');

//FFMPEG

//email send SMTP config
define('MAIL_HOST', 'mail.thecroakers.com');
define('MAIL_USERNAME', 'info@thecroakers.com');
define('MAIL_PASSWORD', '$@Fadi2021@$');
define('MAIL_FROM', 'info@thecroakers.com');
define('MAIL_NAME', 'The Croakers');
define('MAIL_REPLYTO', 'info@thecroakers.com');

define("MEDIA_STORAGE","local");
// if you want to enable AWS s3 then you have to put the value "s3" and if you put "local" videos will be stored in your local server/hosting

define("IAM_KEY","IAM KEY Here");
define("IAM_SECRET","IAM SECRET Here");
define("BUCKET_NAME","qboxus");
define("S3_REGION","us-east-1");

//cloudfront url
//leave empty if you don't want to use cloudfront url
define("CLOUDFRONT_URL",""); //http://d3445ese64nlsm.cloudfront.net/tictic-video

//Paypal

define("PAYPAL_CURRENCY", "USD");
define("PAYPAL_CLIENT_ID", "");
define("PAYPAL_CLIENT_SECRET", "");
?>



