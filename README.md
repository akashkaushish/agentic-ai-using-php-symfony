# Agentic AI Symfony Project

This project is a Docker-based Symfony 7 backend application designed to build and experiment with Agentic AI concepts.

## 🧱 Project Structure
agentic-ai-using-php-symfony/
├── build/
│ ├── app/
│ │ ├── Dockerfile
│ │ └── default.conf
│ └── db/
│ └── orm/init-scripts/
├── docker-compose.yml
└── project/


## 🚀 Setup Instructions

### 1. Clone the repository
git clone <repo-url>
cd agentic-ai-using-php-symfony

### 2. Start Docker
docker compose up -d --build

### 3. Install Symfony
docker compose exec app composer create-project symfony/skeleton .

### 4. Configure Database

Update `.env` inside `/project`:
DATABASE_URL="mysql://symfony:symfony@db:3306/agentic_ai_test?serverVersion=11.0.0-MariaDB&charset=utf8mb4"

### 5. Test Symfony
docker compose exec app php bin/console about

Open in browser:
http://localhost:8080

## 🗄 Database

- Engine: MariaDB 11
- Port: 3307 (local)
- Internal host: `db`

## 📦 Tech Stack

- PHP 8.2
- Symfony 7 (LTS)
- Docker
- Nginx
- MariaDB
- Doctrine ORM

## 🎯 Goal

This project will evolve into an **Agentic AI backend system** using Symfony, APIs, and AI integrations.

---

## 👨‍💻 Author

Aman Kumar