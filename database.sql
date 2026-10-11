CREATE DATABASE IF NOT EXISTS call_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE call_app;
CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  public_id VARCHAR(24) NOT NULL UNIQUE,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS calls (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  room_id CHAR(32) NOT NULL UNIQUE,
  caller_id BIGINT UNSIGNED NOT NULL,
  callee_id BIGINT UNSIGNED NOT NULL,
  status ENUM('ringing','active','ended') NOT NULL DEFAULT 'ringing',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_caller_status (caller_id,status),
  INDEX idx_callee_status (callee_id,status),
  CONSTRAINT fk_calls_caller FOREIGN KEY (caller_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_calls_callee FOREIGN KEY (callee_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS signals (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  room_id CHAR(32) NOT NULL,
  sender_id BIGINT UNSIGNED NOT NULL,
  type ENUM('offer','answer','candidate') NOT NULL,
  payload JSON NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_room_id_id (room_id,id),
  CONSTRAINT fk_signals_room FOREIGN KEY (room_id) REFERENCES calls(room_id) ON DELETE CASCADE,
  CONSTRAINT fk_signals_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
