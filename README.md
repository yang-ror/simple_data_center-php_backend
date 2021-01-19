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

Links and Notes are stored in XML files

  - Read/write/edit to XML file are done by PHP with cookie and post requests.


### Build based on:
 - Ubuntu
 - Apache \ php
 - html \ css \ js
 - bootstrap 5
 - xml


**Live demo**:

**Dockerhub**: [https://hub.docker.com/repository/docker/yangror/simple_data_center](https://hub.docker.com/repository/docker/yangror/simple_data_center)

### Things to know:
 - Inorder to read/write xml file, need to install php-xml
 - Change permissions on ./files/, links.xml, and notes.xml to allow php to read/write
 - Modify php.ini to allow larger file upload(2M on default):
    - upload_max_filesize = 150M
    - post_max_size = 150M
    - max_input_time = 300
    - max_execution_time = 300
