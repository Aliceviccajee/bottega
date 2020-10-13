# lunar-wp-base
Base Wordpress theme and setup using Roots. Comprehensive documentation for Roots can be found [here](root.io).

## Setup

### Requirements
Your machine must have the following dependencies
* Ansible 2.5.3-2.7.5
* Virtualbox >= 4.3.10
* Vagrant >= 2.1.0
* Node.js >= 8.0.0
* Yarn

See the [Roots documentation for installing machine dependencies](https://roots.io/getting-started/docs/macos-basic-setup/).

### Quick setup

* [Fork this repo](https://help.github.com/en/github/getting-started-with-github/fork-a-repo)

* Clone the newly created repo, for example:
````
git clone git@github.com:lunar-build/lunar-wp-base.git
````

* Change directory into project root, for example:
````
cd lunar-wp-base
````

#### Define and build local environment

* Define environment domain and other information in /trellis/group_vars/development/wordpress_sites.yml. You may wish to define a site based on the eventual domain of the project. For example:
````
wordpress_sites:
  local.exciting-project.co.uk:
    site_hosts:
      - canonical: local.exciting-project.co.uk
        redirects:
          - www.local.exciting-project.co.uk
    local_path: ../site # path targeting local Bedrock site directory (relative to Ansible root)
    admin_email: admin@local.exciting-project.co.uk
    multisite:
      enabled: false
    ssl:
      enabled: false
      provider: self-signed
    cache:
      enabled: false
````

* Create a file called .vault_pass at the root of the trellis directory. Paste in the password stored in LastPass, under 'Bottega theme encryption password'.

* Define admin passwords, database passwords and other sensitive data in /trellis/group_vars/development/vault.yml. This has been encrypted in order that it can be committed to the repo.

To edit the development vault file, run the following command:
````
ansible-vault edit trellis/group_vars/development/vault.yml
````
When prompted, supply the Bottega theme encryption password.

* Ensure that the site name matches the one defined in /trellis/group_vars/development/wordpress_sites.yml. For example:
````
vault_mysql_root_password: devpw

# Variables to accompany `group_vars/development/wordpress_sites.yml`
# Note: the site name (`local.exciting-project.co.uk`) must match up with the site name in the above file.
vault_wordpress_sites:
  local.exciting-project.co.uk:
    admin_password: admin
    env:
      db_password: password
````

* For more information about how to deal with encrypted vault.yml files, see [this document](https://roots.io/trellis/docs/vault/).

* Build vagrant box (this will take some time)
````
cd trellis && vagrant up
````

#### Install dependencies and build assets

* Install Bedrock composer packages
````
cd site && composer install
````

* Install theme composer packages
````
cd web/app/themes/sage && composer install
````

* Install theme yarn packages
````
yarn && yarn build
````

* Login to site at, for example, http://local.lunar-wp-base/wp-admin. Your username and password are defined in the file /trellis/group_vars/development/vault.yml. To view this, run:
````
ansible-vault view trellis/group_vars/development/vault.yml
````

* Set theme to Bottega theme
* Activate plugins
* Or, upload an existing database. Database details are found in .env, which will have been generated from the encrypted vault.yml file on vagrant up.

## Plugins
Plugins are version managed as Composer dependencies at the root of the site directory. This can be accomplished in different ways depending on the plugin.

### Installing plugins from the Wordpress Plugin Directory
This is the simplest use case. All plugins from the [WP Plugin Directory](https://wordpress.org/plugins/) are available from the wpackagist-plugin namespace, already defined in site/composer.json. For example:
````
composer require wpackagist-plugin/akismet
````
For more information read this [document](https://roots.io/bedrock/docs/composer/).

### Installing custom or private plugins
See [this document](https://roots.io/wordpress-plugins-with-composer/).

### Installing ACF Pro with encrypted license key
This base theme comes with ACF already installed, with an encrypted license key stored in /trellis/group_vars/development/vault.yml. It does not currently have the same key stored in the other environment vaults - on the assumption that the site will use another license in production, paid for by the client. To understand how this is setup, read [this document](https://roots.io/guides/acf-pro-as-a-composer-dependency-with-encrypted-license-key/).

## Features
* Environment variables set in .env file, not wp_admin
* Additional image sizes are defined in setup.php
* Function to return responsive image (open to improvement). See Controllers/App.php function create_responsive_image()
* ACF Field Builder installed and configured. No more defining fields in the admin panel. See https://roots.io/guides/using-acf-builder-with-sage/ for how to use
* Browser watch. Run ````yarn start```` in the theme folder
* Linter is turned off in resources/assets/build/webpack.config.js. Turn back on if web development isn't frustrating enough for you
* Very lightweight layout strategy defined. Navigate to /sample-page in the browser (uses page.blade.php template) for examples. Feel free to use or delete if not fit for purpose

## Deployment
