## 🛠️ Local Installation & Setup Instructions

You can run this application natively on your machine using standard PHP environment tools:

### Prerequisites
- PHP 8.3 or higher installed locally
- Composer package manager installed
- MySQL database active

### 🗄️ Database Initialization (MySQL)

Before running the application migrations, log into your local MySQL server terminal and execute the following commands to initialize an empty database container:

```sql
-- 1. Create a fresh, empty database for the application
CREATE DATABASE task_manager;

-- 2. (Optional) Verify that the database was successfully created
SHOW DATABASES;
```


### Setup Commands
1. Navigate into the extracted project folder via your terminal.
2. Install the framework package dependencies:
   ```bash
   composer install
   ```
3. Establish your environment configurations by copying the template file:
   ```bash
   cp .env.example .env
   ```
4. Generate your local application encryption key:
   ```bash
   php artisan key:generate
   ```
5. Configure your MySQL credentials inside the `.env` file, then run your database structural schemas:
   ```bash
   php artisan migrate:fresh
   ```
6. Fire up the native, high-speed local development server:
   ```bash
   php artisan serve
   ```
Open your browser and navigate to **`http://127.0.0/tasks`** to view and test the live running application!

---

## 🐳 Docker Production Architecture (DevOps Integration)
For multi-container deployment orchestration tracking, this repository comes pre-packaged with production-ready container configurations. To view or run the isolated containerized build layers, run:
```bash
docker-compose up -d --build
```
