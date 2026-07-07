-- SQL schema for Tigray Volleyball Federation (minimal)
CREATE DATABASE IF NOT EXISTS tvt_db DEFAULT CHARACTER SET utf8mb4;
USE tvt_db;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255),
  role VARCHAR(50),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS news (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  slug VARCHAR(255),
  content TEXT,
  category_id INT,
  published_at DATETIME,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS newsletter_subscribers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Events table for tournaments, matches, trainings
CREATE TABLE IF NOT EXISTS events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255),
  description TEXT,
  type ENUM('match','tournament','training','workshop') DEFAULT 'match',
  start_date DATETIME NOT NULL,
  end_date DATETIME,
  location VARCHAR(255),
  team1_id INT,
  team2_id INT,
  status ENUM('scheduled','ongoing','completed','cancelled') DEFAULT 'scheduled',
  result_team1 INT,
  result_team2 INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255),
  subject VARCHAR(255),
  message TEXT,
  is_read BOOLEAN DEFAULT FALSE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Note: create an admin user using the CLI helper:
-- php backend/create_admin.php admin@example.com Admin@123 "Administrator"

-- Roles table (simple lookup)
CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) UNIQUE NOT NULL,
  description VARCHAR(255)
);

INSERT IGNORE INTO roles (name,description) VALUES
('admin','Full administrator'),
('editor','Content editor'),
('viewer','Read-only');

-- Role permissions mapping
CREATE TABLE IF NOT EXISTS role_permissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role VARCHAR(100) NOT NULL,
  permission VARCHAR(100) NOT NULL,
  UNIQUE KEY(role,permission)
);

INSERT IGNORE INTO role_permissions (role,permission) VALUES
('admin','manage_users'),
('admin','manage_roles'),
('admin','manage_news'),
('admin','manage_events'),
('admin','manage_teams'),
('editor','manage_news');

-- Additional permissions for other admin pages
INSERT IGNORE INTO role_permissions (role,permission) VALUES
('admin','manage_players'),
('admin','manage_fixtures'),
('admin','manage_results'),
('admin','manage_standings'),
('admin','manage_gallery'),
('admin','manage_sponsors'),
('admin','manage_documents'),
('admin','manage_courses'),
('admin','manage_memberships'),
('admin','manage_contacts'),
('admin','manage_newsletter'),
('admin','view_activity_logs'),
('admin','manage_settings'),
('admin','manage_seo'),
('admin','manage_backup');

-- Activity log for audits
CREATE TABLE IF NOT EXISTS activity_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  action VARCHAR(100) NOT NULL,
  target_type VARCHAR(100),
  target_id VARCHAR(100),
  details TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


