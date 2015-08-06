#!/bin/sh
cd /
mkdir data
cd /data
svn checkout https://biodivimage.svn.sourceforge.net/svnroot/biodivimage/trunk/mb32 mb32
cd mb32 
mv www/htaccess.txt www/.htaccess
INSTALL_PATH=`pwd`
INSTALL_PATH=$INSTALL_PATH"/"
to_replace="#php_value auto_prepend_file"
replace_by="php_value auto_prepend_file"
eval sed -i \'s/$to_replace/$replace_by/\' www/.htaccess
to_replace="/my/root/path/"
replace_by=$INSTALL_PATH
args="-i 's|$to_replace|$replace_by|' www/.htaccess"
eval sed $args
cp www/.htaccess ImageServer/
eval sed -i 's/app.server.php/image.server.php/g' ImageServer/.htaccess
echo "Changing permissions to configuration and log..."
sudo chmod -R 775 configuration
sudo chmod -R 775 log
echo "Done"

