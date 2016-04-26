URL Shortener
=============

### What Is It? ###
This is a URL Shortener service you can host at your own site. You type in a long
URL, it gets stored in a database, and a much shorter URL is returned. Both the
client IP address and the link submitted are checked against public blacklists first.


### URL Obfuscator ###
This is a feature that cryptographically generates unique encodings of the
same short URL. This is useful for bypassing browser 301 caching and "visited" link
highlighting among other things.


### Installation ###
The included `database.sql` file can be used to create the required tables in
in MySQL. Right now, there is only one table. The IDs for the URL table are set
to start at 100 by default. This reserves the first 99 IDs (the shortest URLs)
for special use.

The contents of the `short` directory are what needs to be web-addressable. This
can be placed anywhere on your site.

Update `conf.php` in the includes directory. At the very least, change the
database information to match your environment. You will also need to add your
domain to the redirectors list.

The shortener supports both parameterized URLs and "rewrite" URLs. It is best
to internally rewrite the URLs to the parameterized version rather than pass
them straight. If you are passing them directly, obfuscation is not currently
supported. If you are using Apache, use ModRewrite and .htaccess. If you are
on nginx, add rules to your site's config. By default, URLs at the root of
your hostname that start with `!` are shortened and ones that start with `$`
are obfuscated. Normally, this should not interfere with any other content or
rules on your site.

```
# ModRewrite
RewriteRule ^\!([^/]+)/?$ /short/index.php?id=$1 [L]
RewriteRule ^\$(.*)$ /short/index.php?oid=$1 [L]
```

```
# nginx
location @rewrites (
  rewrite ^/\!([^/]+)/?$ /short/index.php?id=$1 last;
  rewrite ^/\$(.*)$ /short/index.php?oid=$1 last;
)
```


### Supporting More Protocols ###
The list of allowed URL protocols is in the configuration file. `http(s)?`, `ftp` and
`mailto` are supported by default. I wouldn't recommend allowing `data` or `javascript`
protocols as there are security risks associated with both. In the case of `data`,
you would essentially be offering free file storage. Don't do that.


### Inspired By ###
This project is inspired by the [ur1.ca](http://ur1.ca/) shortener and
started as a fork of their code. There are some key differences:
* Uses the newer MySQLi PHP module.
* Removed original ID generation code.
* Uses native database IDs and PHP base conversion
* Better performance and collision avoidance
* Added URL obfuscator



TODO
====
*25 April 2016* 

### Hit Counter ###
Need to add hit counter. Since a 301 redirect is being used, only the initial
hit from a client will be counted unless obfuscated URLs are used. This could
also keep track of both types of URL separately.

### Account Management ###
If a hit counter is added, there needs to be a way to log in and see the stats
as well as a way to tie URLs to accounts.

### QR Codes ###
Users should have the option of showing and saving a QR code of their shortened
URL for use in their media.

### Async ###
Convert the form into an asynchronous JavaScript application.

