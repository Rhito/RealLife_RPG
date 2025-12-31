# Deploy RealLife RPG Backend - Free Hosting Guide

## Option 1: Railway.app (Recommended) ⭐

Railway offers a generous free tier perfect for your Laravel app!

### Prerequisites
- GitHub account
- Your code pushed to GitHub
- Railway account (sign up with GitHub)

### Step 1: Prepare Your Laravel App

1. **Create a Procfile** in your project root:
```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

2. **Update `.gitignore`** to include important files:
```
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.phpunit.result.cache
```

3. **Create a `railway.json`** for configuration:
```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "numReplicas": 1,
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

### Step 2: Push to GitHub

```bash
cd c:\Laravel\RealLife_RPG

# Initialize git if not already done
git init
git add .
git commit -m "Initial commit for deployment"

# Create GitHub repo and push
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/RealLife_RPG.git
git push -u origin main
```

### Step 3: Deploy on Railway

1. Go to https://railway.app
2. Click "Start a New Project"
3. Select "Deploy from GitHub repo"
4. Choose your `RealLife_RPG` repository
5. Railway will auto-detect Laravel and start deploying

### Step 4: Add MySQL Database

1. In Railway project, click "New" → "Database" → "MySQL"
2. Railway will create a database and set environment variables automatically

### Step 5: Configure Environment Variables

In Railway, go to your project → Variables tab and add:

```env
APP_NAME="RealLife RPG"
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.railway.app

# Database (Railway auto-sets these)
DB_CONNECTION=mysql
DB_HOST=${MYSQL_HOST}
DB_PORT=${MYSQL_PORT}
DB_DATABASE=${MYSQL_DATABASE}
DB_USERNAME=${MYSQL_USER}
DB_PASSWORD=${MYSQL_PASSWORD}

# Mobile App URLs
MOBILE_URL=realliferpg://
FRONTEND_URL=https://your-app.railway.app

# Mail Settings (use Mailtrap, SendGrid, or Gmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@realliferpg.app
MAIL_FROM_NAME="${APP_NAME}"

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

### Step 6: Generate APP_KEY

Run locally:
```bash
php artisan key:generate --show
```
Copy the output and set it as `APP_KEY` in Railway.

### Step 7: Add Build & Deploy Commands

In Railway → Settings → Deploy:

**Build Command:**
```bash
composer install --optimize-autoloader --no-dev && php artisan config:cache && php artisan route:cache
```

**Start Command:**
```bash
php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## Option 2: Render.com

### Step 1: Create Blueprint File

Create `render.yaml` in project root:

```yaml
services:
  - type: web
    name: reallife-rpg-api
    env: php
    region: oregon
    plan: free
    buildCommand: |
      composer install --optimize-autoloader --no-dev
      php artisan config:cache
      php artisan route:cache
    startCommand: |
      php artisan migrate --force
      php artisan db:seed --force
      php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: APP_KEY
        generateValue: true
      - key: DB_CONNECTION
        value: mysql
      - key: DB_HOST
        fromDatabase:
          name: reallife-rpg-db
          property: host
      - key: DB_DATABASE
        fromDatabase:
          name: reallife-rpg-db
          property: database
      - key: DB_USERNAME
        fromDatabase:
          name: reallife-rpg-db
          property: user
      - key: DB_PASSWORD
        fromDatabase:
          name: reallife-rpg-db
          property: password

databases:
  - name: reallife-rpg-db
    plan: free
```

### Step 2: Deploy to Render

1. Go to https://render.com
2. Click "New" → "Blueprint"
3. Connect your GitHub repo
4. Render will detect `render.yaml` and deploy automatically

---

## Option 3: Free Database - PlanetScale

If using Railway/Render with their database limits, consider PlanetScale:

1. Sign up at https://planetscale.com
2. Create a new database
3. Get connection string
4. Update your `.env` with PlanetScale credentials

---

## Free Email Service - Mailtrap/SendGrid

### Mailtrap (Development)
- Sign up at https://mailtrap.io
- Get SMTP credentials
- Free tier: 500 emails/month

### SendGrid (Production)
- Sign up at https://sendgrid.com
- Free tier: 100 emails/day
- Get API key and configure

---

## Update Mobile App Config

After deployment, update your mobile app `.env`:

```env
# Mobile App .env
EXPO_PUBLIC_API_URL=https://your-app.railway.app/api/v1
```

---

## Testing Your Deployment

```bash
# Test API endpoint
curl https://your-app.railway.app/api/v1/items

# Test password reset
curl -X POST https://your-app.railway.app/api/v1/forgot-password \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com"}'
```

---

## Common Issues & Fixes

### Issue: APP_KEY not set
**Fix:** Generate key locally and add to env vars
```bash
php artisan key:generate --show
```

### Issue: Database migrations fail
**Fix:** Add `--force` flag in deploy command

### Issue: Storage permissions
**Fix:** Ensure storage is writable (Railway/Render handle this automatically)

### Issue: CORS errors from mobile app
**Fix:** Check `cors.php` config allows your mobile app

---

## Cost Breakdown (Free Tiers)

| Service | Free Tier Limits |
|---------|-----------------|
| Railway | $5 credit/month (~500 hours) |
| Render | 750 hours/month |
| PlanetScale | 5GB storage, 1 billion reads |
| Mailtrap | 500 emails/month |
| SendGrid | 100 emails/day |

**Total Cost: $0/month** 🎉

---

## Recommended: Railway + PlanetScale

Best free combination:
- **Railway**: Laravel app hosting
- **PlanetScale**: MySQL database
- **SendGrid**: Email delivery
- **GitHub**: Code repository

This gives you production-ready hosting completely free!

---

## Next Steps

1. Choose a platform (Railway recommended)
2. Push your code to GitHub
3. Deploy using the steps above
4. Update mobile app with production URL
5. Test all features

Need help with any step? Just ask! 🚀
