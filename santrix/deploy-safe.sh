#!/bin/bash
# SAFE DEPLOYMENT SCRIPT (Non-Destructive)
# Use this for servers with existing projects.

read -p "Enter Domain (e.g. santrix.my.id): " INPUT_DOMAIN
DOMAIN=${INPUT_DOMAIN:-santrix.my.id}

echo "🚀 Starting Safe Deployment for $DOMAIN..."

# 1. Setup Folder & Code
echo "📂 Setting up Codebase..."
mkdir -p /var/www
cd /var/www
if [ -d "santrix" ]; then
    echo "Updating existing code..."
    cd santrix
    git config --global --add safe.directory /var/www/santrix
    git pull origin main
else
    echo "Cloning fresh code..."
    git clone https://github.com/mahinutsmannawawi20-svg/santrix.git santrix
    cd santrix
fi

# 2. Permissions
echo "🔒 Fixing Permissions..."
chown -R www-data:www-data /var/www/santrix
chmod -R 775 storage bootstrap/cache

# 3. Environment & Database
echo "⚙️ Configuring Environment..."
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Force Update .env
sed -i "s/APP_URL=.*/APP_URL=https:\/\/$DOMAIN/" .env
sed -i "s/APP_ENV=.*/APP_ENV=production/" .env
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=santrix/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=santrix/" .env
# Set Safe Password
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=SantrixSecure2025/" .env

echo "🗄️ Setting up Database..."
# Using password SantrixSecure2025 (No special chars to avoid bash error)
mysql -e "CREATE DATABASE IF NOT EXISTS santrix;"
mysql -e "CREATE USER IF NOT EXISTS 'santrix'@'localhost' IDENTIFIED BY 'SantrixSecure2025';"
mysql -e "GRANT ALL PRIVILEGES ON santrix.* TO 'santrix'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# 4. Install Dependencies
echo "📦 Installing Dependencies (App Only)..."
# Run as www-data for safety
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data php artisan key:generate
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan storage:link

echo "🎨 Building Assets..."
npm install
npm run build

# 5. Nginx Configuration (Safe Mode)
echo "🌐 Configuring Nginx..."
# Create santrix file DOES NOT touch default
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

# Enable & Reload
ln -sf /etc/nginx/sites-available/santrix /etc/nginx/sites-enabled/
# Verify config before reload
nginx -t && systemctl reload nginx

echo "✅ DEPLOYMENT SUCCESS!"
echo "Domain: $DOMAIN"
echo "Database Password: SantrixSecure2025"
echo "Please run Certbot manually for SSL."
