CREATE DATABASE cbit_placement;
USE cbit_placement;

-- Students
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_number VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    branch VARCHAR(50),
    year VARCHAR(10),
    phone VARCHAR(15),
    avatar VARCHAR(255) DEFAULT 'avatar.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Companies
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    logo VARCHAR(255),
    industry VARCHAR(50),
    avg_ctc DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Experiences
CREATE TABLE experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    company_id INT,
    role VARCHAR(100),
    difficulty ENUM('easy','medium','hard') DEFAULT 'medium',
    rounds JSON,
    tips TEXT,
    status ENUM('selected','rejected','pending') DEFAULT 'pending',
    likes INT DEFAULT 0,
    views INT DEFAULT 0,
    is_approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (company_id) REFERENCES companies(id)
);

-- Demo Data
INSERT INTO students VALUES 
(1,'21CS001','demo@cbit.ac.in','Demo Student','$2y$10$92IXUNpkjO0rOQ5byMi.Ye','CSE','3','demo','avatar.png',NOW()),
(2,'21CS002','admin@cbit.ac.in','Admin User','$2y$10$92IXUNpkjO0rOQ5byMi.Ye','CSE','4','admin','admin.png',NOW());

INSERT INTO companies VALUES 
(1,'Google','google.png','Tech',45.00,NOW()),
(2,'Microsoft','microsoft.png','Tech',35.00,NOW());
