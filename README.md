## Installation

1. [**Access Your Server**](https://www.bing.com/search?form=SKPBOT&q=Access%20Your%20Server)

Open your command line interface and navigate to the root directory of your Adobe Commerce installation.

2. [**Require the Package**](https://www.bing.com/search?form=SKPBOT&q=Require%20the%20Package)

Run the following Composer command to require the Magnalister extension package:

```shell
composer require redgecko/magnalister

This command is accessible on Packagist, which is the default package repository for Composer. You can find the Magnalister package details and versions on its Packagist page.

1. 
Update Composer

After adding the requirement, update Composer to install the extension:

composer update

1. 
Enable the Extension

Enable the Magnalister extension by executing the following Magento commands:

php bin/magento module:enable RedGecko_Magnalister
php bin/magento setup:upgrade

1. 
Clear Cache

Clear the cache to ensure that the changes take effect:

php bin/magento cache:clean

1. 
Verify the Installation

Verify that the extension is installed and enabled by checking the module status:

php bin/magento module:status RedGecko_Magnalister

You should see a message indicating that the module is enabled.

Post-Installation
After installing the Magnalister extension, you can configure it from the Adobe Commerce Admin Panel under Stores > Configuration > Magnalister.

For further assistance or troubleshooting, refer to the official Magnalister documentation or contact their support team.


We hope this guide helps you successfully install the Magnalister extension for Adobe Commerce. If you encounter any issues, please consult the documentation or reach out for support.