#! /bin/bash
echo "Enter mysql user's name"
read arg1
echo "Enter mysql user's password"
read -s arg2
echo "Enter Mysql database name (for instance MB32)"
read arg3

cd /
mkdir data
cd /data
echo "Downloading Morphbank website files..."
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

echo "Installing the sample database..."
cd /tmp
wget "https://sourceforge.net/projects/biodivimage/files/mb32sample.sql.gz" --no-check-certificate
gunzip mb32sample.sql.gz
mysql_create_user="GRANT ALL ON $arg3.* TO $arg1@localhost IDENTIFIED BY '$arg2';"
echo $mysql_create_user > mysql_temp
echo "Logging in Mysql as root..."
mysql -u root -h localhost -p < mysql_temp
rm mysql_temp
mysql_create_db="create database $arg3;"
echo $mysql_create_db > mysql_temp
echo "Creating tables..."
mysql -u $arg1 -h localhost -p$arg2 < mysql_temp
cd /data/mb32
mysql -u $arg1 -h localhost -p$arg2 $arg3 < "configuration/dbscripts/createTablesMB32.sql"
echo "Creating Object Procs..."
mysql -u $arg1 -h localhost -p$arg2 $arg3 < "configuration/dbscripts/createObjectProcs.sql"
echo "Creating Procs..."
mysql -u $arg1 -h localhost -p$arg2 $arg3 < "configuration/dbscripts/createProcs.sql"
echo "Importing sample database..."
mysql -f -u $arg1 -h localhost -p$arg2 $arg3 < "/tmp/mb32sample.sql"
echo "Done"
echo "Remember to save login information for $arg1"


