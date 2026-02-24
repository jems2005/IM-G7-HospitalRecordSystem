-- ============================================
-- TCH Medical Center - Hospital Record System
-- Database Schema + Sample Data
-- ============================================

CREATE DATABASE IF NOT EXISTS tch_hospital_db;
USE tch_hospital_db;

-- ============================================
-- Table 1: patients (6 columns incl. timestamps)
-- ============================================
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- Table 2: doctors (6 columns incl. timestamps)
-- ============================================
CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- Table 3: appointments (6 columns incl. timestamps)
-- ============================================
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

-- ============================================
-- Table 4: medical_records (6 columns incl. timestamps)
-- ============================================
CREATE TABLE IF NOT EXISTS medical_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    diagnosis VARCHAR(255) NOT NULL,
    treatment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);

-- ============================================
-- Sample Data: patients
-- ============================================
INSERT INTO patients (full_name, date_of_birth, gender) VALUES
('Juan dela Cruz', '1985-03-15', 'Male'),
('Maria Santos', '1990-07-22', 'Female'),
('Pedro Reyes', '1978-11-05', 'Male'),
('Ana Gonzales', '2000-01-30', 'Female'),
('Carlos Mendoza', '1965-09-18', 'Male'),
('Liza Bautista', '1995-04-12', 'Female'),
('Ramon Torres', '1982-06-25', 'Male'),
('Elena Villanueva', '1973-12-08', 'Female');

-- ============================================
-- Sample Data: doctors
-- ============================================
INSERT INTO doctors (full_name, specialization, contact_number) VALUES
('Dr. Jose Rizal', 'Cardiology', '09171234567'),
('Dr. Corazon Aquino', 'Pediatrics', '09182345678'),
('Dr. Andres Bonifacio', 'Orthopedics', '09193456789'),
('Dr. Emilio Aguinaldo', 'Neurology', '09204567890'),
('Dr. Gabriela Silang', 'Dermatology', '09215678901');

-- ============================================
-- Sample Data: appointments
-- ============================================
INSERT INTO appointments (patient_id, doctor_id, appointment_date) VALUES
(1, 1, '2025-01-15 09:00:00'),
(2, 2, '2025-01-16 10:30:00'),
(3, 3, '2025-01-17 11:00:00'),
(4, 4, '2025-01-18 14:00:00'),
(5, 5, '2025-01-19 15:30:00'),
(6, 1, '2025-01-20 09:30:00'),
(7, 2, '2025-01-21 11:30:00'),
(8, 3, '2025-01-22 13:00:00');

-- ============================================
-- Sample Data: medical_records
-- ============================================
INSERT INTO medical_records (patient_id, diagnosis, treatment) VALUES
(1, 'Hypertension', 'Prescribed Amlodipine 5mg once daily. Advised low-sodium diet and regular exercise.'),
(2, 'Upper Respiratory Infection', 'Prescribed Amoxicillin 500mg thrice daily for 7 days. Rest and increased fluid intake.'),
(3, 'Fractured Left Arm', 'Applied cast. Prescribed Ibuprofen 400mg for pain. Follow-up in 4 weeks.'),
(4, 'Migraine', 'Prescribed Sumatriptan 50mg. Advised to avoid triggers and maintain sleep schedule.'),
(5, 'Type 2 Diabetes', 'Prescribed Metformin 500mg twice daily. Strict diet control and blood sugar monitoring.'),
(6, 'Eczema', 'Prescribed Hydrocortisone cream. Advised to use fragrance-free products.'),
(7, 'Lower Back Pain', 'Physical therapy recommended. Prescribed Naproxen 250mg twice daily.'),
(8, 'Anemia', 'Prescribed Ferrous Sulfate 325mg once daily. Iron-rich diet recommended.');
