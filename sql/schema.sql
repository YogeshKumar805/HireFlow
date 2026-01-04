CREATE DATABASE IF NOT EXISTS pw_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pw_crm;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('ADMIN','MANAGER') NOT NULL DEFAULT 'MANAGER',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE candidates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  emp_id VARCHAR(20) NOT NULL UNIQUE,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(120) NOT NULL,
  mobile VARCHAR(15) NOT NULL,
  whatsapp VARCHAR(15) NOT NULL,
  designation VARCHAR(120) NOT NULL,
  interview_taken_by VARCHAR(120) NOT NULL,

  salary DECIMAL(10,2) NULL,
  joining_date DATE NULL,

  status ENUM('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING',

  offer_pdf_path VARCHAR(255) NULL,
  offer_generated_at DATETIME NULL,
  offer_download_token VARCHAR(64) NULL,

  created_by_user_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX(status),
  INDEX(created_at),
  FOREIGN KEY (created_by_user_id) REFERENCES users(id)
);
