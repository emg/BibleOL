#!/bin/bash

#
# Set up myapp/config/database.php
#
do_database_config() {
    # Copy myapp/config/database.php-dist to myapp/config/database.php,
    # preserving permissions (-p)
    cp -p myapp/config/database.php-dist myapp/config/database.php

    # Change 'USERNAME' to the value of the environment variable
    # ${MYSQL_USER}.
    # -i means: Change the file directly
    sed -i -e "s_'USERNAME'_'${MYSQL_USER}'_g" myapp/config/database.php

    
    # Change 'PASSWORD' to the value of the environment variable
    # ${MYSQL_PASSWORD}.
    sed -i -e "s_'PASSWORD'_'${MYSQL_PASSWORD}'_g" myapp/config/database.php

    # Change 'DATABASE' to the value of the environment variable
    # ${MYSQL_DATABASE}.
    sed -i -e "s_'DATABASE'_'${MYSQL_DATABASE}'_g" myapp/config/database.php
}

do_ol_config() {
    # Copy myapp/config/database.php-dist to myapp/config/database.php,
    # preserving permissions (-p)
    cp -p myapp/config/ol.php-dist myapp/config/ol.php

    # Generate a salt for the passwords
    # This is just the first 9 characters in the md5sum of the current date
    PW_SALT=`date | md5sum | cut -c 1-8`

    echo "PW_SALT=${PW_SALT}"

    # Replace the pwsalt value with the contents of PW_SALT
    sed -i -e "s/'xxxxxxx'/'${PW_SALT}'/g" myapp/config/ol.php

    # Replace the
    sed -i -e "s/'xxxxx@xxxxx.xx'/'${MAIL_SENDER_ADDRESS}'/g"
}


# Call the function that sets up myapp/config/database.php
echo "Doiung database_config"
do_database_config;
echo "Done database_config"

echo "Doiung ol_config"
do_ol_config;
echo "Done ol_config"






