# Minimalistic Wechat JSSDK API server + its Frontend script

## Description

* Simple API for wx JSSDK + its frontend js plugin for a lightning fast JSSDK functionnalities implementation
* Designed to  
    * have no dependancies, no database connection (working using json to store appid/secret)
    * be stupid simple to use, edit and upgrade, based on no framework, pure vanilla php and js
    * be a standalone microservice on a unique url for all your H5 and websites (ex http://api.wx.mycompany.cn) 
* the frontend part contains a watcher that will do a call to the api if it detects the URI changed (for VueJS and other Frontend frameworks)

## Why

* Wechat JSSDK API implementation on websites and H5 is a pain, requiring backend, storage and frontend logic.
* Using this system, you can setup once and for all your websites and H5 an API system, the only thing needed is adding the wxSharing.js on your pages.
* It is also compatible with VueJS, Angular and React with its watcher that detect URL changes.

## Installation
1. **Setup API Server**
   1. copy config.example.php to config.php
   2. SECURE the keys folder that nobody outside your script can access it

   > ```
   > on Nginx you can use :
   > location /keys {
   >  deny all;
   > }
   > ```

2. **On your Application/Website** 
   1. add `<script src="http://api.wx.yourcompany.cn/script/wxSharing.js"></script>` in your frontend app (on vueJS, simply include it inside your index.html)
   2. call in your pages

   > ```
   > window.wxSharing = {
   >     debug: true,
   >     apiUrl: 'http://api.wx.yourcompany.cn',
   >     appid: 'wx56e08b08894f0d35',
   >     title: 'hello',
   >     desc: 'world',
   >     link: 'http://preprod.website.31ten.cn', 
   >     imgUrl: 'http://preprod.website.31ten.cn/static/img/wechat-sharing.png', // Absolute url only
   >     success: function // optional,
   >     cancel: function  // optional
   >   }
   > ```

### NOTE
the window.wxSharing can be setuped later, after the script loading and changed dynamically on the pages, the script includes a watcher that will take the changes in account

## API ENDPOINTS

1. getsignature

    * Example: api/index.php?action=getsignature&appid=wx56e1111111111&appurl=http://preprod.tmall.31ten.cn&signuri=/test.html
    * Description: get the signature necessary to use JSSDK on a page, Used automatically by script/wxSharing.js

2. clean 

    * Example: api/index.php?action=clean&appid=wx56e1111111111&password=yourpasswordhere
    * Description: Clean the JS token and JS ticket for the selected APPID, that next time 'getsignature' is called, it refreshs it

