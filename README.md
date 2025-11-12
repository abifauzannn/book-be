## ⚙️ Backend (Laravel API) Setup

### 1️⃣ Clone the Repository

git clone <repo-url>
cd book-be

#### 2️⃣ Install Dependencies

composer install

#### 3️⃣ Copy Environment File

cp .env.example .env

### 4️⃣ Generate Application Key

php artisan key:generate

### 5️⃣ Generate JWT Secret

php artisan jwt:secret

### 6️⃣ Create Database

CREATE DATABASE book_be;

DB_DATABASE=book_be
DB_USERNAME=root
DB_PASSWORD=

### 7️⃣ Run Migrations

php artisan migrate

### 8️⃣ Seed Initial Data

php artisan db:seed

### 9️⃣ Start the Development Server

php artisan serve
