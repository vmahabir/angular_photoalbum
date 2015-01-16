# angular_photoalbum
Angular Photoalbum

# Database setup
 1. First run api/database/database.sql to create the database and tables
 2. Then run create-user.sql to create the user and give it permissions on the database

 When there are changes to the DB schema file, you only need to run step 1 - step 2 (create user) only needs to be run once.

## To get up and running:
cd /path/to/project
bower install
npm install

`If that doesn't work you probably miss some dependencies:`

sudo apt-get ruby-full rubygems
sudo gem install sass
sudo gem install compass
npm install -g bower
npm install -g grunt-cli

# Installing and using the Yeoman generator

npm install -g yo
npm install -g generator-angular

yo angular:controller testCtrl
yo angular:route testroute
yo angular:view testView
etc...

See the [GitHub page](https://github.com/yeoman/generator-angular)