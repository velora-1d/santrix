#!/bin/bash

# SANTRIX AUTO DEPLOYMENT SCRIPT
# Run this on fresh Ubuntu 22.04 LTS as root

# --- CONFIGURATION ---
read -p "Enter your Domain Name (e.g. santrix.my.id): " INPUT_DOMAIN
DOMAIN=${INPUT_DOMAIN:-santrix.my.id}
echo "Using Domain: $DOMAIN"

REPO="https://github.com/mahinutsmannawawi20-svg/santrix.git"
DB_NAME="santrix"
DB_USER="santrix"
DB_PASS="SantrixSecurePass2025!" # Ganti password ini nanti
# ---------------------

echo "🚀 Starting Deployment for $DOMAIN..."

# 1. Update System & Install Dependencies
echo "📦 Installing LEMP Stack & Dependencies..."
apt update && apt upgrade -y
apt install -y nginx mariadb-server git unzip curl
apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-curl php8.2-mbstring php8.2-xml php8.2-zip php8.2-bcmath php8.2-intl php8.2-gd

# 2. Install Composer
echo "🎼 Installing Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# 3. Setup Database
echo "🗄️ Configuring Database..."
mysql -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;"
mysql -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';"
mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# 4. Setup Node.js (for asset build)
echo "📦 Installing Node.js..."
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# 5. Clone Project
echo "📂 Cloning Repository..."
mkdir -p /var/www
cd /var/www
if [ -d "santrix" ]; then
    echo "Directory exists, pulling latest changes..."
    cd santrix
    git pull origin main
else
    git clone $REPO santrix
    cd santrix
fi

# 6. Configure Permissions
echo "🔒 Setting Permissions..."
chown -R www-data:www-data /var/www/santrix
chmod -R 775 /var/www/santrix/storage /var/www/santrix/bootstrap/cache

# 7. App Setup
echo "⚙️ Configuring Laravel..."
# Copy .env if not exists
if [ ! -f .env ]; then
    cp .env.example .env
    # Auto-update DB creds
    sed -i "s/DB_DATABASE=santrix/DB_DATABASE=$DB_NAME/" .env
    sed -i "s/DB_USERNAME=root/DB_USERNAME=$DB_USER/" .env
    sed -i "s/DB_PASSWORD=/DB_PASSWORD=$DB_PASS/" .env
    sed -i "s/APP_URL=http:\/\/localhost/APP_URL=https:\/\/$DOMAIN/" .env
    sed -i "s/APP_ENV=local/APP_ENV=production/" .env
    sed -i "s/APP_DEBUG=true/APP_DEBUG=false/" .env
fi

# Run as www-data to avoid permission issues
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data php artisan key:generate
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan storage:link

# Build Assets
npm install
npm run build

# 8. Nginx Configuration
echo "🌐 Configuring Nginx..."
cat > /etc/nginx/sites-available/santrix <<EOF
server {
    listen 80;
    server_name $DOMAIN *.$DOMAIN;
    root /var/www/santrix/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Activate Site
ln -sf /etc/nginx/sites-available/santrix /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl restart nginx

echo "✅ DEPLOYMENT COMPLETE!"
echo "Database Password: $DB_PASS"
echo "Run 'certbot --nginx' manually to setup SSL."
