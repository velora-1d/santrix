#!/bin/bash
# =====================================================
# DEPLOYMENT SCRIPT - Dashboard Riyadlul Huda
# =====================================================

# CONFIGURATION
DOMAIN="your-domain.com"     # 🔴 GANTI INI
VPS_IP="your-vps-ip"         # 🔴 GANTI INI
DB_PASSWORD="your-db-password" # 🔴 GANTI INI

set -e  # Exit on any error

echo "🚀 Starting Laravel Deployment to $DOMAIN ($VPS_IP)..."

# ===== 1. UPDATE SYSTEM =====
echo "📦 Updating system packages..."
apt update && apt upgrade -y

# ===== 2. INSTALL NGINX =====
echo "🌐 Installing Nginx..."
apt install nginx -y
systemctl enable nginx
systemctl start nginx

# ===== 3. INSTALL MYSQL =====
echo "🗄️ Installing MySQL..."
apt install mysql-server -y
systemctl enable mysql
systemctl start mysql

# ===== 4. INSTALL PHP 8.2 =====
echo "🐘 Installing PHP 8.2..."
apt install software-properties-common -y
add-apt-repository ppa:ondrej/php -y
apt update
apt install php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd php8.2-intl -y

# ===== 5. INSTALL COMPOSER =====
echo "🎼 Installing Composer..."
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
else
    echo "Composer already installed."
fi

# ===== 6. INSTALL NODE.JS =====
echo "📗 Installing Node.js..."
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
    apt install nodejs -y
else
    echo "Node.js already installed."
fi

# ===== 7. CLONE REPOSITORY =====
echo "📥 Setting up repository..."
cd /var/www
if [ ! -d "dashboard-riyadlul-huda" ]; then
    git clone https://github.com/mahinutsmannawawi20-svg/dashboard-riyadlul-huda.git
else
    echo "Directory exists, pulling changes..."
    cd dashboard-riyadlul-huda
    git pull origin main
    cd ..
fi

cd dashboard-riyadlul-huda

# ===== 8. INSTALL DEPENDENCIES =====
echo "📚 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "📦 Installing Node dependencies & building assets..."
npm install
npm run build

# ===== 9. CONFIGURE LARAVEL =====
echo "⚙️ Configuring Laravel..."
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
    echo "⚠️  PLEASE EDIT .env FILE WITH DATABASE CREDENTIALS!"
fi

# ===== 10. SET PERMISSIONS =====
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/dashboard-riyadlul-huda
chmod -R 755 /var/www/dashboard-riyadlul-huda
chmod -R 775 storage bootstrap/cache

# ===== 11. CREATE NGINX CONFIG =====
echo "📝 Creating Nginx config..."
cat > /etc/nginx/sites-available/dashboard-riyadlul-huda << EOF
server {
    listen 80;
    server_name $DOMAIN;
    root /var/www/dashboard-riyadlul-huda/public;

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

ln -sf /etc/nginx/sites-available/dashboard-riyadlul-huda /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx

echo ""
echo "✅ ============================================="
echo "✅ DEPLOYMENT COMPLETE!"
echo "✅ ============================================="
echo ""
echo "📌 NEXT STEPS:"
echo "1. Setup Database:"
echo "   sudo mysql -u root"
echo "   CREATE DATABASE riyadlul_huda;"
echo "   CREATE USER 'admin'@'localhost' IDENTIFIED BY '$DB_PASSWORD';"
echo "   GRANT ALL PRIVILEGES ON riyadlul_huda.* TO 'admin'@'localhost';"
echo "   FLUSH PRIVILEGES;"
echo "   EXIT;"
echo ""
echo "2. Edit .env file:"
echo "   nano /var/www/dashboard-riyadlul-huda/.env"
echo "   (Set DB_DATABASE=riyadlul_huda, DB_USERNAME=admin, DB_PASSWORD=$DB_PASSWORD)"
echo ""
echo "3. Run Migrations:"
echo "   cd /var/www/dashboard-riyadlul-huda"
echo "   php artisan migrate --seed"
echo ""
echo "4. Setup SSL (HTTPS):"
echo "   apt install certbot python3-certbot-nginx"
echo "   certbot --nginx -d $DOMAIN"
echo ""
