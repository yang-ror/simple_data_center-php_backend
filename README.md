# Sample Data Center

A sample data center with PHP backend. Deploy to an apache server to host it, or simply pull the docker container. 

It has four major parts:

 - Files
 - Images
 - Links
 - Notes

Files are stored in ./files/

 - Any new files copied into this directory will be automatically discovered by index.php and provide a download link for each of them.

Images are also stored in ./files/ as regular files.

 - (jpg, jpeg, gif, png) will be automatically identified by index.php and display a preview for each image.

Links and Notes are stored in json files

  - Read/write/edit to json files are done by PHP with post requests.


### Build based on:
 - Ubuntu
 - Apache
 - php
 - jQuery
 - html \ css \ js
 - bootstrap 5


***Live demo***: [http://ec2-3-129-148-1.us-east-2.compute.amazonaws.com:3080](http://ec2-3-129-148-1.us-east-2.compute.amazonaws.com:3080)

***Dockerhub***: [https://hub.docker.com/repository/docker/yangror/simple_data_center](https://hub.docker.com/repository/docker/yangror/simple_data_center)

### Things to know:
 - Change permissions on ./files to allow php to read/write
 - Modify php.ini to allow larger file upload(2M on default):
    - upload_max_filesize = 150M
    - post_max_size = 150M
    - max_input_time = 300
    - max_execution_time = 300

### Change log:
 - 2021-03-15:
  1. Optimized for mobile view

 - 2021-03-16:
  1. Store data in MongoDB instead of xml file

 - 2021-03-17:
  1. Now sends new note as post request since cookie and get have a size limit of 4096 bytes
  2. Fixed a bug that links won't be truncated when there are no files on the server

- 2021-03-18:
  1. Show a progress bar when uploading, and added jQuery and jQuery-Form library to achieve this
  2. Now store data in json files instead of MongoDB, since MongoDB is not supported on 32-bit platforms
  3. Fixed a bug where deleteFile.php doesn't work when filename contains '&' or '+'
  4. css and javascript are now independent files in their sub-directories
  5. move php files for backend operation to the php sub-directory
  6. combine deleteLink.php and deleteNote.php to one php file, delete.php

