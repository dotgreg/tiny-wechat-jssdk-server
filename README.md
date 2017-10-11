# Tiny Wechat JSSDK API server + its frontend script

## Description

* Simple API for wx JSSDK + its frontend js plugin for a lightning fast JSSDK functionnalities implementation

* Designed to :
    * Be used as a tool for **rapid prototyping** on wechat JSSDK, **small websites and H5** quick solution
    * Have **no dependencies**, no database connection (working using json to store ticket/token)
    * Be **stupid simple to use**, edit and upgrade, based on no framework, pure vanilla php and js
    * Be a standalone microservice api (ex https://api.wx.mycompany.cn) **work with several APPIDs**, **use a unique api for all your H5 and websites**    
    * Be **Frontend Framework-friendly** (VueJS, React, Angular) as the Javascript wxSharing.js includes a **watcher** that will update the signature if a URI change is detected
    * **Easen the task of JSSDK debugging**, especially on workflows requiring compilation before reaching the server production (Webpack or MeteorJS), as the js script can be embedded as external js ressource from your app on https://api.wx.mycompany.cn/script/wxSharing.js, thus easily editable (same design than the https://www.google-analytics.com/analytics.js embed)

## Why

* Wechat JSSDK API implementation on websites and H5 **is a pain**, requiring backend, storage and frontend logic.
* **Debugging wechat JSSDK is even more a nightmare, as it usually requires you to debug your js code on the production server** (as it only works on whitelisted domains). Adding it to some workflows **where compiling is required before deployment (like Webpack or MeteorJS)**, the task could easily takes hours, thus **editing the JS script on a third party server you have total control on it** solves a lot of issues.
* Using this system, you can setup once and for all your websites and H5 an API system, the only thing needed is adding the wxSharing.js on your pages.

## Installation

### Setup API Server

   1. Make sure to whitelist your server IP inside Wechat Official Account backend
   
   2. Edit your config by copying config.example.php to config.php
   
   3. Secure the `keys` folder that nobody outside your script can access it, it will contain the Tokens and Tickets
   

   > ```
   > on Nginx you can use :
   > location /keys {
   >  deny all;
   > }
   > ```
   

### On your Application/Website 

   1. Make sure to whitelist your Domain name as JSSDK Allowed URL inside Wechat Official Account backend
   
   2. Add `<script src="https://api.wx.yourcompany.cn/script/wxSharing.js"></script>` in your frontend app (on vueJS/React/Angular, simply include it inside your root index.html)
   
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


## API Endpoints

1. getsignature

    * **Example**: `api/index.php?action=getsignature&appid=wx56e1111111111&appurl=https://mycompany.cn&signuri=/home.html`
    * **Description**: Get the signature necessary to use JSSDK on a page, used internally by script/wxSharing.js

2. clean 

    * **Example**: `api/index.php?action=clean&appid=wx56e1111111111&password=yourpasswordhere`
    * **Description**: Clean the JS token and JS ticket for the selected APPID, that next time 'getsignature' is called, it refreshs it

