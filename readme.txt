=== All-in-One WP Migration ===
Contributors: yani.iliev, bangelov, pimjitsawang
Tags: move, transfer, copy, migrate, backup, clone, restore, db migration, wordpress migration, website migration, database export, database import, apoyo, sauvegarde, di riserva, バックアップ
Requires at least: 3.3
Tested up to: 5.2
Requires PHP: 5.2.17
Stable tag: 7.5
License: GPLv2 or later

Move, transfer, copy, migrate, and backup a site with 1-click. Quick, easy, and reliable.

== Description ==
This plugin exports your WordPress website including the database, media files, plugins and themes with no technical knowledge required.
Upload your site to a different location with a drag and drop in to WordPress.
There is an option to apply an unlimited number of find and replace operations on your database during the export process. The plugin will also fix any
serialisation problems that occur during the find/replace operation.

Mobile device compatible: All in One WP Plugin is the first plugin to offer true mobile experience on WordPress versions 3.3 and up.

= No limitations on host or operating system =
* We have tested the plugin on the major Linux distributions, MacOS and Microsoft Windows.
* [Please see the list of hosting providers that we work with.](https://help.servmask.com/knowledgebase/supported-hosting-providers/)

= Bypass all upload size restriction =
* We use chunks to import your site data. Most providers set the maximum upload file size to 2MB. As the file restrictions are only applied to each chunk, webserver upload size restrictions are bypassed by keeping the chunks under 2MB to easily upload your entire site.

= Zero Dependencies =
* The plugin does not require any PHP extensions and works with all versions of PHP from v5.2 onwards. This is great news for v5.2 users who are unsupported by many other products.

= Support for MySQL and MySQLi =
* No matter what php mysql driver your webserver ships with, we support it.

= Compatible with WordPress v3.3 to present =
* We have a comprehensive Quality Assurance and testing process that ensures that the plugin is always compatible with the latest release of WordPress, but we don't support versions of WordPress prior to version 3.3 (2012)

= WP-CLI Integration is available in Unlimited Extension =
* [WP-CLI Integration Documentation](https://help.servmask.com/knowledgebase/cli-integration/)

= Support =
* For the community version of the plugin please watch the instruction videos below and see our FAQ.
* If you have more complex requirements, our team is here to help. If you have any questions please feel free to get in touch at [help.servmask.com](https://help.servmask.com/)
* All premium products include premium support.

= Migrate WordPress to cloud storage services using our completely new premium extensions =
**All of the Cloud Storage and Multisite extensions include premium support and the Unlimited extension free of charge**

* [File](https://import.wp-migration.com)
* [Unlimited](https://servmask.com/products/unlimited-extension)
* [Dropbox](https://servmask.com/products/dropbox-extension)
* [Multisite](https://servmask.com/products/multisite-extension)
* [FTP](https://servmask.com/products/ftp-extension)
* [Google Drive](https://servmask.com/products/google-drive-extension)
* [Amazon S3](https://servmask.com/products/amazon-s3-extension)
* [URL](https://servmask.com/products/url-extension)
* [OneDrive](https://servmask.com/products/onedrive-extension)
* [Box](https://servmask.com/products/box-extension)
* [Mega](https://servmask.com/products/mega-extension)
* [DigitalOcean Spaces](https://servmask.com/products/digitalocean-spaces-extension)
* [Backblaze B2](https://servmask.com/products/backblaze-b2-extension)
* [Google Cloud Storage](https://servmask.com/products/google-cloud-storage-extension)
* [Microsoft Azure Storage](https://servmask.com/products/microsoft-azure-storage-extension)
* [Amazon Glacier](https://servmask.com/products/amazon-glacier-extension)
* [pCloud](https://servmask.com/products/pcloud-extension)
* [WebDAV](https://servmask.com/products/webdav-extension)
* [S3 Client](https://servmask.com/products/s3-client-extension)

= Supported hosting providers =
**The plugin does not have any dependencies, making it compatible with all PHP hosting providers. We support a vast range of hosting providers. Some of the most popular include:**

* DigitalOcean
* Bluehost
* InMotion
* Web Hosting Hub
* Siteground
* Pagely
* Dreamhost
* Justhost
* GoDaddy
* WP Engine
* Site5
* 1&1
* Pantheon
* [See the full list of supported providers here](https://help.servmask.com/knowledgebase/supported-hosting-providers/)

= Contact us =
* [Get free help from us here](https://servmask.com/help)
* [Report a bug or request a feature](https://servmask.com/help)
* [Find out more about us](https://servmask.com)

[youtube http://www.youtube.com/watch?v=BpWxCeUWBOk]

[youtube http://www.youtube.com/watch?v=mRp7qTFYKgs]

== Installation ==
1. All-in-One WP Migration can be installed directly through your WordPress
Plugins dashboard.
1. Click "Add New" and Search for "All-in-One WP Migration"
1. Install and Activate

Alternatively you can download the plugin using the download button on this page and then upload the all-in-one-wp-migration folder to the /wp-content/plugins/ directory then activate throught the Plugins dashboard in WordPress

== Screenshots ==
1. Mobile Export page
2. Mobile Import page
3. Plugin Menu

== Privacy Policy ==
All-in-One WP Migration **asks for your consent** to collect **requester's email address** when filling plugin's contact form. [GDPR Compliant Privacy Policy](https://www.iubenda.com/privacy-policy/946881)

== Changelog ==
= 7.5 =
**Changed**

* Improved i18n support for non-latin filenames

= 7.4 =
**Fixed**

* Small bug when reporting an issue

**Changed**

* Remove "www" from emails on import

= 7.3 =
**Changed**

* Wrap size_format in a function to handle corner cases

= 7.2 =
**Fixed**

* Use a nonce when checking for updates

= 7.1 =
**Fixed**

* Allow only users with export an import capability to see the list of backups. This issue was introduced in version 7.0 (Thanks to Ed from siliconforks for reporting)

= 7.0 =
**Added**

* DB_CHARSET and DB_COLLATE are stored in the backup
* Display a notice when logged in user is administrator without export/import capabilities

**Fixed**

* Escape backup labels. (Thanks to Connum for reporting)

**Changed**

* Create backup button on Backups page starts a full export.

= 6.97 =
**Added**

* ARIA support

**Fixed**

* Download button position

= 6.96 =
**Fixed**

* Delete failed import/exports older than 24 hours

= 6.95 =
**Changed**

* Remove the cleanup of failed imports. It causes some of the imports to fail

= 6.94 =
**Fixed**

* Decrease memory use during export and import of the database
* Wait 5 seconds longer for servers to process export/import jobs

**Changed**

* Removed emoticon from Import success screen

= 6.93 =
**Changed**

* Simplified the text on successful import
* Improved the speed of exporting the database

**Removed**

* Feedback option from the sidebar

= 6.92 =
**Added**

* Workaround for PHP 7.3.2 bug when database export uses more memory https://bugs.php.net/bug.php?id=77597

= 6.91 =
**Changed**

* Reverted monkey-patched fix for some GoDaddy hosting plans. The issue was fixed by GoDaddy yesterday

= 6.90 =
**Changed**

* Improved URL replacement
* Improved compatibility with some of GoDaddy hosting plans

= 6.89 =
**Changed**

* Tested up to WordPress 5.1

= 6.88 =
**Fixed**

* Table data type issue on export/import
* PHP notice on custom backup labels

= 6.87 =
**Added**

* Custom backup labels on Backups page
* Support for OptimizePress

**Fixed**

* Translation text on Import page

= 6.86 =
**Added**

* Support for MySQL BIT field type

**Fixed**

* WP CLI issue on export/import

= 6.85 =
**Changed**

* Tested up to WordPress 5.1

= 6.84 =
**Fixed**

* Set the type of backup file during download via HTML attribute
* Removed Math_BigInteger class which was causing issues with other plugins

= 6.83 =
**Added**

* Check for minimum required extension version on import
* Disable Join My Multisite, MultiSite Clone Duplicator and WordPress MU Domain Mapping plugins after restoring a backup

**Fixed**

* Support for WordPress 3.3
