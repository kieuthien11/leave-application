# 🚀 Laravel + Docker Quickstart

Dự án Laravel được cấu hình sẵn với Docker. Bạn chỉ cần vài bước đơn giản là đã có thể khởi chạy môi trường phát triển hoàn chỉnh.

---

## ⚙️ Hướng dẫn khởi chạy

### 1. Khởi động Docker containers

```bash
docker compose up -d
```

### 2. Truy cập vào container Laravel
```bash
docker exec -it laravel_app bash
```

### 3. Tạo key ứng dụng
```bash
php artisan key:generate
```

### 4. Chạy migration để tạo bảng
```bash
php artisan migrate
```

### 5. Seed dữ liệu mẫu
```bash
php artisan db:seed
```