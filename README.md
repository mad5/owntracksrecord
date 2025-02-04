# ownTracks PHP recorder for http

you may know owntracks (https://owntracks.org/booklet/)  
you may want to use it because it provides your data on your own server.  
You are a skilful PHP developer but have no desire to install additional software.  
You simply want to continue working with the data and need a very simple backend.

## HTTP instead of MQTT

during my research I always heard the question whether MQTT is used and why not and that would be much easier and so on in most of the enquiries about the configuration and the format of the json-response and how it works with the ‘friends’.

thanks, but no thanks. I don't want to install any more software, no matter how ‘easy’ it is. I'm very familiar with PHP, have Apache and NGINX under control and have my own ideas about how I store and process the data. Anyone who is happy with MQTT is welcome to use it, I would prefer to use HTTP.

I am really grateful for this great software that the ownTracks team has provided and only want to use the existing way via HTTP.

The piece of code presented here is not a complete application for a specific purpose, but it shows how the data can be received by the app and how a meaningful response can look like so that the map and friends list is filled correctly in the app.  

## Configure ownTracks

* set the mode to: "HTTP"

* set the endpoint to your record.php-address: https://www.example.com/owntracks/record.php

* create a data-directory next to record.php

* set your device-ID e.g. JohnPixel
 
* set your tracker-ID pxj

## Handle friends names and profile-photos

in the record.php-file there is a part for matching each tracker-ID with a readable name and a profile-photo.

```php
// Define an associative array mapping IDs to base64-encoded PNG strings for faces
$faces = [
    "nx4" => "base64PNGCode",
];

// Define an associative array mapping IDs to readable names
$names = [
    "nx4" => "readable Name",
];
```

Of course you can add a complex administration and use a database. But if you want to network just the 4 devices in your family, then this might be a practical solution :-)

In any case, it is a good basis from which you can develop further.

