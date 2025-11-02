# Testo_voe setup guide

## Project setup
1. Clone repo
2. Create .env from .env.example
3. Build and run `make buildup`
4. Seed db `make seed`
5. Generate Swagger doc `make swagger`

- API http://127.0.0.1:8080/api
- API token `4oqOGoEUc0tCdrEmgVmEOaX6CCaMzkTYMCKw`
- Swagger docs http://127.0.0.1:8080/api/documentation

Stack: Laravel 11.3, Roadrunner 2025, PHP 8.3, Swagger

### Makefile doc
- Restart container `make restart`
- Build, up containers `make buildup`
- Up containers `make up`
- Down containers `make down`
- Run migrations `make migrate`
- Rollback last migration `make rollback`
- Open PHP artisan tinker `make tinker`
- Open bash terminal inside container `make bash`
- Seed db `make seed`
- Generate Swagger doc `make swagger`
