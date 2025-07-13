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

**build tag docker**
docker build -t thienkn/leave-application-app:latest .

**push docker register**
docker push thienkn/leave-application-app:latest

**dump db from docker**
docker exec -i laravel_db mysqldump -u root -proot laravel > laravel.sql

**import db to docker**
docker exec -i laravel_db mysql -u root -proot laravel_01 < laravel.sql

**cp file db to k8s**
kubectl port-forward service/db 3307:3306

**cp file db to k8s**
kubectl cp laravel.sql mysql-fcfbb8c4-bk2rv:/laravel.sql

**import db to k8s**
kubectl exec -it mysql-fcfbb8c4-bk2rv -- bash