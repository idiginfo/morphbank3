#! /bin/bash
echo "Enter where Morphbank is installed (usually /data/mb32)"
read MB_install
echo "Enter mysql user's name"
read arg1
echo "Enter mysql user's password"
read -s arg2
echo "Enter Mysql database name (for instance MB32)"
read arg3

cd /tmp
wget "https://sourceforge.net/projects/biodivimage/files/mb32sample.sql.gz"
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
cd $MB_install
mysql -u $arg1 -h localhost -p$arg2 $arg3 < "configuration/dbscripts/createTablesMB32.sql"
echo "Creating Object Procs..."
mysql -u $arg1 -h localhost -p$arg2 $arg3 < "configuration/dbscripts/createObjectProcs.sql"
echo "Creating Procs..."
mysql -u $arg1 -h localhost -p$arg2 $arg3 < "configuration/dbscripts/createProcs.sql"
echo "Importing sample database..."
mysql -f -u $arg1 -h localhost -p$arg2 $arg3 < "/tmp/mb32sample.sql"
echo "Done"
echo "Remember to save login information for $arg1"

