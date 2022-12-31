# Configuration d'Apache

> **Le fichier de configuration utilisé en production est disponible dans `docs\web\conf`.**

```sh
# Remove actual default site
sudo a2dissite 000-default
sudo rm -f /etc/apache2/sites-available/000-default.conf

# Copy conf file to apache into sites-available folder
sudo cp /path/to/your/app/docs/web/conf/000-default.conf  /etc/apache2/sites-available

# Enable application site
sudo a2ensite 000-default

# Restart Apache service
sudo systemctl reload apache2

# Apache Rights
sudo chown -R ${USER}:www-data storage/ public/ bootstrap/
```

*Documentation réalisée sur Ubuntu 20.04*

**Pour que la configuration soit effective il est nécessaire de réaliser les modifications suivantes dans le fichier `000-default.conf`**

```diff
-	# ServerName www.example.com
+   ServerName www.your-url.com

-	DocumentRoot "/var/www/html/public"
+   DocumentRoot "/path/to/your/app"

-	<Directory "/var/www/html">
+   <Directory "/path/to/your/app">
```