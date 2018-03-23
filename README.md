# drupal.docker.template
Project template for a Docker stack to setup a Drupal project.

This repository is a template that you can take and add it to your existing projects by copying the content of this repository into your project's directory customizing. Then you commit it to the git tree.

1. Copy `.docker` and `docker-*.yml` files
2. Copy `settings.local.example-dev.php` to `docroot/sites/default/files/settings.local.php`
3. Copy `settings.local.example-dev.php` to `docroot/sites/default/files/example.settings.local.php`

You can adapt the files above to your project, by:

1. Define the correct `server_name` for your project in `.docker/conf-nginx/project.conf`, for example:

```
server_name  PROJECT_NAME.local PROJECT_NAME.org PROJECT_NAME.ro PROJECT_NAME.eu;
```

2. Enable/Disable services you need from `docker-compose.yml`

Commit everything in your directory tree.


## Project setup

This section explains how to work with your newly adapted project.

### Step 1. Setup hosts file to create a new DNS name for the project

Edit the hosts file and add a new entry as it was declared in nginx's `server_name` above.

```
127.0.0.1  PROJECT_NAME.local
```

- UNIX/Linux/Mac edit /etc/hosts, 
- Windows %WINDOWS%/system32/drivers/etc/hosts

### Step 2. Configure your project’s Docker configuration file

Copy `docker-compose.override.example.yml` to a new file named `docker-compose.override.yml` where you can customize container names, ports, volumes etc.

Start the stack:

```
docker-compose up.
```

Notes:

- On Fedora and some other Linux distros, you need to execute `docker` commands as the `root` account.
- By default the database container is initialized with a database called `drupal`, where you can install the Drupal database.
- Some projects might not use the *Apache Solr* container and you can just comment it to avoid using up resources.

### Step 3. Setup Drupal database

For a new project, just follow the usual Drupal installation guidelines via web interface - http://PROJECT_NAME.local/install.php.

For an existing project get the database from another Drupal instance like staging or production. Use `drush` command to make a database dump:

```
cd /var/www/html/PROJECT_NAME/docroot
drush sql-dump --gzip > ~/PROJECT_NAME-latest.sql.gz
```

To install the dump into the `db` container:

```
 # docker cp PROJECT_NAME-latest.sql.gz db:/
 # docker exec -ti ex_db bash`
 # gunzip -c PROJECT_NAME-latest.sql.gz | mysql -p drupal
 ```

### Step 4. Create a `settings.php` file

For a new project Drupal will create a settings.php for you, however it is always a good idea to add your specific settings override in a separate file. Therefore at the end of `settings.php` add the following snippet:

```php
/* Keep this code block at the end of this file to take full effect. */
if (file_exists(__DIR__ . '/settings.local.php')) {
  include __DIR__ . '/settings.local.php';
}
```

For existing projects you can create one with the content from example `settings.local.example-dev.php` in this repo.


## Development tasks

### Restore the Drupal "files"

For existing files, you can restore the "files" by getting an archive from other environments and deflate them into `sites/default/files` folder.

**Note:** Fix permission to allow the docker container to write to: 

```
chmod -R 33:33 `docroot/sites/default/files`
```

### Using Drush

Below are some common Drupal commands to help you speed-up development:

- `drush uli <uid|username>` - Get a one-time login link to reset password or log in as another user.
- `drush upwd --password=newpass <uid|username>` - Reset the password for an user
- `drush rsync @ENV:%files @self:%files` - Sync the "files" folder with other instances (prod, test, staging etc.).
- `drush sql-sycnc @ENV @self` - Sync database with another instance you have access to


**Note on remote access**

To access remote instances you need to configure SSH access from the `php` docker container to the remote server, therefore you need to:

1. Have system administrator setup an account for you on the remote server and add your key to `authorized_keys`.
2. To access directly from `php` container you can mount your private key in `/root/.ssh/id_rsa` (example in `docker-compose.overrite.example.yml`)
3. For Windows watch out for permissions on the `/root/.ssh` folder structure.


