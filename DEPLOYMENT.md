# Demo Deployment Guide

This Laravel app is prepared for a Docker-based demo deployment on Render.

## Recommended Demo Host

Use Render Web Service with Docker because this project is a dynamic Laravel application. GitHub Pages will not run PHP or Laravel.

## 1. Push The Latest Deployment Files

```powershell
git add .dockerignore Dockerfile docker DEPLOYMENT.md
git commit -m "Add Render deployment setup"
git push
```

## 2. Generate An App Key

Run this locally and copy the output:

```powershell
php artisan key:generate --show
```

## 3. Create The Render Service

1. Go to Render Dashboard.
2. Create a new Web Service.
3. Connect this GitHub repository:
   `https://github.com/ShahriarRKhan/Student-Result-Management-System`
4. Select runtime/language: Docker.
5. Branch: `main`.

## 4. Environment Variables

For a simple demo using SQLite:

```text
APP_NAME=Student Result Management System
APP_ENV=production
APP_KEY=base64:paste-your-generated-key-here
APP_DEBUG=false
APP_URL=https://your-render-url.onrender.com
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
```

For a more durable demo, create a PostgreSQL database on Render and use:

```text
DB_CONNECTION=pgsql
DB_URL=your-render-internal-postgres-url
```

Keep `APP_KEY` secret. Do not commit it to GitHub.

## 5. Deploy

Click Deploy. Render will build the Docker image, run migrations during startup, and publish a public `onrender.com` URL.

After deployment, update `APP_URL` in Render to match the final public URL and redeploy once.
