# Deploy RealLife RPG Backend - Free Hosting Guide

## Best Free Options for Laravel (2024)

### 🥇 Recommended: Railway.app
- ✅ Free $5/month credit (renews monthly)
- ✅ MySQL included
- ✅ Easy deployment
- ✅ Automatic HTTPS
- ⏱️ Deploy time: ~10 minutes

### 🥈 Alternative: Render.com
- ✅ Free tier available
- ✅ PostgreSQL included
- ✅ Auto-deploy from GitHub
- ⚠️ Web service sleeps after 15 min inactivity

---

## Option 1: Deploy to Railway (Recommended)

### Prerequisites
1. GitHub account
2. Railway account (sign up at railway.app)

### Step 1: Prepare Your Code

**1. Create `.gitignore` (if not exists):**
```gitignore
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.phpunit.result.cache
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log
/.idea
/.vscode
```

**2. Create `Procfile` in project root:**
```
web: php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
worker: php artisan queue:work --sleep=3 --tries=3
```

**3. Create `nixpacks.toml` in project root:**
```toml
[phases.setup]
nixPkgs = ['php82', 'php82Packages.composer']

[phases.install]
cmds = ['composer install --no-dev --optimize-autoloader']

[phases.build]
cmds = ['php artisan config:clear', 'php artisan cache:clear']

[start]
cmd = 'php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}'
```

### Step 2: Push to GitHub

```bash
cd c:\Laravel\RealLife_RPG
git init
git add .
git commit -m "Initial commit for deployment"

# Create new repo on GitHub, then:
git remote add origin https://github.com/YOUR_USERNAME/reallife-rpg-backend.git
git branch -M main
git push -u origin main
```

### Step 3: Deploy on Railway

1. **Go to** https://railway.app
2. **Click** "Start a New Project"
3. **Choose** "Deploy from GitHub repo"
4. **Select** your repository
5. **Railway will auto-detect** Laravel and deploy!

### Step 4: Add MySQL Database

1. **In Railway Dashboard**, click "+ New"
2. **Select** "Database" → "MySQL"
3. **Railway will** auto-create and link the database

### Step 5: Configure Environment Variables

In Railway project → Variables tab, add:

```env
APP_NAME="RealLife RPG"
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app

DB_CONNECTION=mysql
# These are auto-filled by Railway:
# DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

LOG_CHANNEL=stack
LOG_LEVEL=error

MOBILE_URL=realliferpg://
FRONTEND_URL=https://your-app-name.railway.app

MAIL_MAILER=smtp
# Configure with your email provider
```

**Generate APP_KEY:**
```bash
php artisan key:generate --show
```

### Step 6: Run Migrations

In Railway → Service → Terminal:
```bash
php artisan migrate --force
php artisan db:seed --class=ItemSeeder
php artisan db:seed --class=AchievementSeeder
php artisan db:seed --class=UserSeeder
```

---

## Option 2: Deploy to Render.com

### Step 1: Prepare Code (Same as Railway)

Create the same files above, plus:

**Create `build.sh`:**
```bash
#!/usr/bin/env bash

composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

Make it executable:
```bash
chmod +x build.sh
```

### Step 2: Deploy on Render

1. **Go to** https://render.com
2. **Create** new Web Service
3. **Connect** GitHub repository
4. **Configure:**
   - Environment: PHP
   - Build Command: `./build.sh`
   - Start Command: `php artisan serve --host=0.0.0.0 --port=$PORT`

5. **Add PostgreSQL** (free tier includes it)

6. **Set Environment Variables** (same as Railway)

---

## Post-Deployment Steps

### 1. Update Mobile App API URL

Update `c:\Laravel\RealLife_RPG_Mobile\.env` or config:
```
EXPO_PUBLIC_API_URL=https://your-app-name.railway.app/api/v1
```

### 2. Test Your Deployment

```bash
# Test API endpoint
curl https://your-app-name.railway.app/api/v1/items

# Test health
curl https://your-app-name.railway.app/
```

### 3. Configure Email (Optional)

For production emails, use:
- **Mailtrap** (free tier)
- **SendGrid** (free tier: 100 emails/day)
- **Mailgun** (free tier: 5,000 emails/month)

Update `.env` on Railway:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourapp.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Troubleshooting

### Issue: 500 Error
```bash
# Check logs in Railway/Render dashboard
# Usually caused by:
# 1. Missing APP_KEY
# 2. Database connection error
# 3. Missing .env variables
```

### Issue: Database Connection Failed
```bash
# Railway auto-provides DB variables
# Make sure DB_CONNECTION=mysql (or pgsql for Render)
# Check Railway database tab for credentials
```

### Issue: Storage/Cache Permissions
```bash
# Run in Railway terminal:
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

---

## Free Tier Limitations

| Service | Free Tier | Limitations |
|---------|-----------|-------------|
| **Railway** | $5/month credit | ~500 hours/month uptime |
| **Render** | Free tier | Spins down after 15min inactivity |
| **Fly.io** | Free allowances | 3 shared-cpu VMs |

---

## Quick Start Commands

```bash
# 1. Generate APP_KEY
php artisan key:generate --show

# 2. Create GitHub repo
git init
git add .
git commit -m "Deploy to production"

# 3. Push to GitHub
git remote add origin YOUR_GITHUB_URL
git push -u origin main

# 4. Deploy on Railway/Render (follow steps above)

# 5. Run migrations remotely
# (Use Railway/Render web terminal)
php artisan migrate --force
php artisan db:seed
```

---

## Next Steps After Deployment

1. ✅ Test all API endpoints
2. ✅ Update mobile app API URL
3. ✅ Configure email service
4. ✅ Set up domain (optional)
5. ✅ Enable queue worker for background jobs
6. ✅ Set up monitoring (Railway provides built-in metrics)

Your backend will be live at: `https://your-app-name.railway.app`

🎉 **Your mobile app can now connect from anywhere!**
