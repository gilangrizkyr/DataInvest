---
description: Langkah-langkah cepat untuk deploy aplikasi menggunakan Docker
---

# Workflow Deployment DataInvest

1. Siapkan file environment
```bash
cp .env.example .env
```
2. Sesuaikan konfigurasi di `.env` (manual)

// turbo
3. Bangun dan jalankan Docker
```bash
docker-compose up -d --build
```

// turbo
4. Jalankan Migrasi
```bash
docker exec -it datainvest php spark migrate
```

// turbo
5. Jalankan Seeder
```bash
docker exec -it datainvest php spark db:seed UserSeeder
```
