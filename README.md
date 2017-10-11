# Minimalistic Wechat JSSDK API server + its Frontend script

## Description

* Simple API for wx JSSDK + its frontend js plugin for a lightning fast JSSDK functionnalities implementation

* Designed to :
    * Be used as a tool for **rapid prototyping** on wechat JSSDK, **small websites and H5** quick solution
    * Have **no dependancies**, no database connection (working using json to store ticket/token)
    * Be **stupid simple to use**, edit and upgrade, based on no framework, pure vanilla php and js
    * Be a standalone microservice, **use a unique api for all your H5 and websites** (ex http://api.wx.mycompany.cn)    
    
* Frontend part contains a watcher that will do a call to the api if it detects the URI changed (for VueJS and other Frontend frameworks)

## Why

* Wechat JSSDK API implementation on websites and H5 is a pain, requiring backend, storage and frontend logic.
* Using this system, you can setup once and for all your websites and H5 an API system, the only thing needed is adding the wxSharing.js on your pages.
* It is also compatible with VueJS, Angular and React with its watcher that detect URL changes.

## Installation
1. **Setup API Server**
   1. Make sure to whitelist your server IP inside Wechat Official Account backend
   2. Copy config.example.php to config.php
   3. Secure the keys folder that nobody outside your script can access it

   > ```
   > on Nginx you can use :
   > location /keys {
   >  deny all;
   > }
   > ```
   

2. **On your Application/Website** 
   1. Make sure to whitelist your Domain name as JSSDK Allowed URL inside Wechat Official Account backend
   2. Add `<script src="https://api.wx.yourcompany.cn/script/wxSharing.js"></script>` in your frontend app (on vueJS, simply include it inside your index.html)
   3. Configure the object window.wxSharing in each of your page, working as well on Frontend frameworks using History.push (VueJS, Reach, Angular) as the script regularly checks the URL and call the API if any change

   > ```
   > window.wxSharing = {
   >     debug: true,
   >     apiUrl: 'https://api.wx.yourcompany.cn',
   >     appid: 'wx56e08b08894f0d35',
   >     title: 'hello',
   >     desc: 'world',
   >     link: 'https://mywebsite.cn', 
   >     imgUrl: 'https://mywebsite.cn/static/img/wechat-sharing.png', // Absolute url only
   >     success: function // optional,
   >     cancel: function  // optional
   >   }
   > ```

## API ENDPOINTS

1. getsignature

    * **Example**: `api/index.php?action=getsignature&appid=wx56e1111111111&appurl=https://mycompany.cn&signuri=/home.html`
    * **Description**: Get the signature necessary to use JSSDK on a page, used internally by script/wxSharing.js

2. clean 

    * **Example**: `api/index.php?action=clean&appid=wx56e1111111111&password=yourpasswordhere`
    * **Description**: Clean the JS token and JS ticket for the selected APPID, that next time 'getsignature' is called, it refreshs it

