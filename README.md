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

Links and Notes are stored in Mongodb

  - Read/write/edit to mongodb documents are done by PHP with post requests.


### Build based on:
 - Ubuntu
 - Apache \ php
 - composer
 - html \ css \ js
 - bootstrap 5
 - Mongodb


***Live demo***: [http://ec2-3-129-148-1.us-east-2.compute.amazonaws.com:3080](http://ec2-3-129-148-1.us-east-2.compute.amazonaws.com:3080)

***Dockerhub***: [https://hub.docker.com/repository/docker/yangror/simple_data_center](https://hub.docker.com/repository/docker/yangror/simple_data_center)

### Things to know:
 - Change permissions on ./files to allow php to read/write
 - Modify php.ini to allow larger file upload(2M on default):
    - upload_max_filesize = 150M
    - post_max_size = 150M
    - max_input_time = 300
    - max_execution_time = 300
