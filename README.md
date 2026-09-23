# Book World Store

Laravel-based e-commerce site for [bookworldstore.com](https://bookworldstore.com).

## Local development

Requires Docker Desktop.

```bash
cp .env.example .env
docker compose up -d --build
```

The site is then available at http://localhost:8080, Mailpit (catches outgoing dev email) at http://localhost:8025.

## Production

Deployed via Docker Compose (`docker-compose.prod.yml`) on a VPS, with a self-hosted GitHub Actions runner auto-deploying on every push to `main` (see `.github/workflows/deploy.yml`).
