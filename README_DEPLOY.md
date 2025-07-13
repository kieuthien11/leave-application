# 🚀 Leave Application - Triển khai ứng dụng & xử lý cơ sở dữ liệu

File này hướng dẫn toàn bộ quá trình triển khai ứng dụng `leave-application-app` bằng Docker và Kubernetes, bao gồm cả sao lưu và khôi phục cơ sở dữ liệu MySQL từ môi trường Docker sang Kubernetes.

---

## ✅ Các bước thực hiện từ đầu đến cuối

---

### 🏗️ 1. Build Docker Image

Tạo image từ source code trong thư mục hiện tại:

```bash
docker build -t thienkn/leave-application-app:latest .
```

### ☁️ 2. Push Docker Image lên Docker Hub
Sau khi build thành công, đẩy image lên Docker Hub:

```bash
docker push thienkn/leave-application-app:latest
```

### 🗄️ 3. Dump Database từ Docker Container
```bash
docker exec -i laravel_db mysqldump -u root -proot laravel > laravel.sql
```

### 🧪 4. (Tuỳ chọn) Kiểm tra lại việc Import vào DB khác trong Docker
```bash
docker exec -i laravel_db mysql -u root -proot laravel_01 < laravel.sql
```

### 🔀 5. Kết nối tới Pod MySQL trong Kubernetes (port-forward)
```bash
kubectl port-forward service/db 3307:3306
```

### 📂 6. Copy File SQL từ máy host vào Pod MySQL
```bash
kubectl cp laravel.sql mysql-fcfbb8c4-bk2rv:/laravel.sql
```

### 💾 7. Import File SQL trong Pod MySQL
Truy cập vào shell trong pod:
```bash
kubectl exec -it mysql-fcfbb8c4-bk2rv -- bash
mysql -u root -proot laravel < /laravel.sql
```

kubectl apply -f web-deployment.yaml


 kubectl create configmap nginx-config --from-file=default.conf=./default.conf


kubectl apply -f leave-app-deployment.yaml
kubectl apply -f leave-app-service.yaml
kubectl apply -f web-deployment.yaml
kubectl apply -f web-service.yaml
kubectl apply -f leave-app-application.yaml -n argocd

kubectl delete configmap nginx-config

kubectl create configmap nginx-config --from-file=default.conf=./default.conf

kubectl port-forward service/laravel-web-service 8888:80

kubectl port-forward svc/argocd-server -n argocd 8080:443

