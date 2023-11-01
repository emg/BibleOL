FROM ubuntu:22.04

MAINTAINER ulrikp

#
# Build a container which is able to run Bible Online Learner.
#

# First, we make the installation Noninteractive, so as to not stop,
#  e.g., when configuring tzdata.
#
# See https://github.com/phusion/baseimage-docker/issues/58#issuecomment-47995343
#
RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections

# Then, we ensure that Ubuntu is up-to-date
RUN apt-get update && apt-get upgrade -y 

# Then we install the dependencies
#
RUN apt-get install -y \
    apache2 php-dev php-sqlite3 \
    mysql-client php-intl php-xml \
    mysql-server php-json git \
    php php-mbstring curl \
    php-curl php-mysql \
    systemctl

# Now we start the Apache2 server
RUN systemctl enable apache2.service
RUN systemctl start apache2.service



# Then add and install the Emdros .deb package.
# 
# This must first be built with the ../Build_Emdros/
#
# See the Makefiles for how to do this.
#
RUN install -m 755 -d /root/emdros_deb

# The emdros_deb/* should contain the emdros .deb package
#
# This should have been taken care of by the Makefiles.
ADD emdros_deb/* /root/emdros_deb/

#
# Now install the Emdros .deb, and enable the EmdrosPHP8 module.
#
RUN dpkg -i /root/emdros_deb/*.deb && \
    phpenmod EmdrosPHP8

#
# Then we clone BibleOnlineLearner, including adding submodules
# recursively.
#
#
# Also, run the git-hooks/setup.sh script, which does further setup,
# including downloading some databases.
#
RUN cd /var/www/html/ && \
   git clone --recursive https://github.com/EzerIT/BibleOL && \
   cd BibleOL && \
   /bin/bash git-hooks/setup.sh


#
# Prepare a file in /src/secrets/ that can be used to pass to the
# mysql(1) client.
#
RUN install -m 755 -d /srv/secrets/ && \
    install -m 600 /dev/null /srv/secrets/mysql_root@localhost.cnf && \
    echo "[client]" >> /srv/secrets/mysql_root@localhost.cnf && \
    echo password="${MYSQL_ROOT_PASSWORD}" >> /srv/secrets/mysql_root@localhost.cnf

#
# Start the MySQL service.
# Then configure a username and
# create an empty database, then granting all privileges
# to the BOL MySQL user.
#
# Is it enabled? If not, enable it.
RUN systemctl is-enabled mysql.service || systemctl enable mysql.service
RUN systemctl status  mysql.service || systemctl start mysql.service


# Change the password of the MYSQL root user
RUN echo "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '${MYSQL_ROOT_PASSWORD}';" | mysql

#
# Create a BOL MySQL user
#
RUN echo "CREATE USER '${MYSQL_BOL_USER_USERNAME}'@'host' IDENTIFIED WITH authentication_plugin BY '${MYSQL_BOL_USER_PASSWORD}';" | mysql --defaults-extra-file=/srv/secrets/root@localhost.cnf \
    --user=root \
    --host=localhost \
    --no-auto-rehash

#
# Create a MySQL database
#
RUN echo "CREATE DATABASE ${MYSQL_BOL_DATABASE_NAME};" | mysql --defaults-extra-file=/srv/secrets/root@localhost.cnf \
    --user=root \
    --host=localhost \
    --no-auto-rehash

#
# Grant all MySQL privileges on the BOL MySQL database
# to the MySQL user
#
RUN echo "GRANT ALL PRIVILEGES ON ${MYSQL_BOL_DATABASE_NAME}.* TO '${MYSQL_BOL_USER_USERNAME}'@'localhost';" | mysql --defaults-extra-file=/srv/secrets/root@localhost.cnf \
    --user=root \
    --host=localhost \
    --no-auto-rehash 
    
    




CMD /bin/bash
