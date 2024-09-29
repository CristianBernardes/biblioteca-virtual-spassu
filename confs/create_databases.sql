CREATE DATABASE IF NOT EXISTS biblioteca CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
CREATE DATABASE IF NOT EXISTS biblioteca_test CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
GRANT ALL PRIVILEGES ON biblioteca.* TO 'biblioteca'@'%';
GRANT ALL PRIVILEGES ON biblioteca_test.* TO 'biblioteca'@'%';
FLUSH PRIVILEGES;
