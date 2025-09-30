# 🚀 Laravel Application Template

A **Laravel 12** application template with **pre-configured development tools**, **Domain-Driven Design structure**, and ready-to-use **CI/CD workflows**.

---

## ✨ Features

- ⚡ **Laravel 12** – Latest Laravel framework
- 🐘 **PHP 8.4**
- 🏗 **Domain-Driven Design** – Clean and organized domain structure
- 🔄 **Pre-configured CI/CD** – GitHub Actions workflows
- 🧹 **Code Quality Tools** – Larastan, Pint, Duster
- 🧪 **Testing Framework** – Pest with Laravel plugin

---

## 🚦 Getting Started

1. Click **"Use this template"** on GitHub
2. Create a new repository from this template
3. Clone your new repository locally
4. Run setup:

```bash
composer install 
npm install # (for pre-commit scripts)
cp .env.example .env 
php artisan key:generate 
php artisan migrate
```

---

## 📦 Pre-Installed Libraries

### 🔹 Production Dependencies
- [**Spatie Laravel Data**](https://github.com/spatie/laravel-data) (^4.17) – Data transfer objects
- [**Spatie Laravel MediaLibrary**](https://github.com/spatie/laravel-medialibrary) (^11.15) – Media/file management
- [**Spatie Laravel Flare**](https://github.com/spatie/laravel-flare) (^2.2) – Error tracking
- [**Laravel Sanctum**](https://laravel.com/docs/sanctum) (^4.0) – API authentication
- [**Laravel Postmark (Coconut Craig)**](https://github.com/coconutcraig/laravel-postmark) (^3.3) – Email integration
- [**Threls Check-Env**](https://github.com/threls/check-env) (^1.1) – Environment validation

### 🔹 Development Dependencies
- [**Larastan**](https://github.com/nunomaduro/larastan) (^3.0) – Static analysis
- [**Laravel Pint**](https://laravel.com/docs/pint) (^1.24) – Code formatting
- [**Laravel Pail**](https://github.com/laravel/pail) (^1.2.2) – Real-time log viewer
- [**Pest**](https://pestphp.com/) (^4.1) – Testing framework
- [**Pest Laravel Plugin**](https://pestphp.com/docs/laravel) (^4.0) – Laravel-specific Pest features
- [**Tighten Duster**](https://github.com/tighten/duster) (^3.2) – Code quality tools

---

## ⚙️ GitHub Workflow Templates

This template ships with **workflow templates** for CI/CD:

### ✅ CI Workflow (`.github/workflows/ci.yml`)
Runs on **push** and **pull requests**:
- ✅ Pest tests
- ✅ Pint code style check
- ✅ Larastan static analysis
- ✅ Duster code quality

---

### 🔍 Environment Validation (`.github/workflows/validate-env.yml.template`)
- Ensures required environment variables are present
- Activate by renaming to `validate-env.yml`

---

### ☁️ Vapor Deployment (`.github/workflows/vapor-deploy.yml.template`)
- Automated **Laravel Vapor** deployments
- Activate by renaming to `vapor-deploy.yml`
- Deploys automatically on:
    - `develop` → Development
    - `staging` → Staging
    - `main` → Production
- Requires `VAPOR_API_TOKEN` secret

---

### 🛠 Vapor Configuration (`vapor.yml.template`)
- Pre-configured for **development**, **staging**, and **production**
- Includes:
    - Database & queue setup
    - Media storage (DigitalOcean / AWS S3)
    - Asset compilation via npm

---

## 🪝 Git Hooks (Husky)

This template includes **Husky** pre-commit hooks for code quality.

### 🔹 Active Hook (`.husky/pre-commit`)
Runs automatically on commit:
- **Lint-staged**: Applies linters only to staged files
- Ensures:
    - Pint runs on staged PHP files
    - Other configured linters are applied

---

### 🔹 Optional Env Check Hook (`.husky/pre-commit-check-envs.template`)
Validates environment variables before committing.

**Enable it:**
```bash
cp .husky/pre-commit-check-envs.template .husky/pre-commit-check-envs
```
Then update `.husky/pre-commit` to include this check.

---

## 🧹 Auto-Linting
> All staged files are **auto-linted before commit**, ensuring clean and consistent code.  
