# Service setup guide

### Service class that implements ShippingServiceInterface interface location - App/Services
api - swagger or `POST /api/fulfill-seller-order`

## Project setup
1. Clone repo
2. Create .env from .env.example
3. Build and run `make buildup`
4. Seed db `make seed`
5. Generate Swagger doc `make swagger`

- Swagger docs http://127.0.0.1:8080/api/documentation

- API http://127.0.0.1:8080/api
- API token `4oqOGoEUc0tCdrEmgVmEOaX6CCaMzkTYMCKw`


Stack: Laravel 11.3, Roadrunner 2025, PHP 8.3, Swagger
