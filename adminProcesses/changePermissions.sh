#!/bin/bash
# change ownership and  permissions on jpeg, jpg, thumbs and tiff directories to apache, and 775 

IFS=$'\n'
chown -R apache /data/images/

chmod -R 775 /data/images/
