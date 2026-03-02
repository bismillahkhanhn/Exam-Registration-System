CREATE DATABASE IF NOT EXISTS exam_registration_system;
USE exam_registration_system;

-- Students table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usn VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    birth_date DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    department ENUM(
        'Computer Science',
        'Electronics and Communication',
        'Electrical Engineering',
        'Mechanical Engineering',
        'Civil Engineering'
    ) NOT NULL,
    semester INT NOT NULL,
    phone VARCHAR(15) NOT NULL,
    image_path VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Subjects table
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department ENUM(
        'Computer Science',
        'Electronics and Communication',
        'Electrical Engineering',
        'Mechanical Engineering',
        'Civil Engineering'
    ) NOT NULL,
    semester INT NOT NULL,
    subject_code VARCHAR(20) NOT NULL,
    name VARCHAR(100) NOT NULL,
    credits INT NOT NULL,
    exam_date DATE NOT NULL
);

-- Registered subjects table
CREATE TABLE registered_subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    UNIQUE KEY unique_registration (student_id, subject_id)
);

-- Insert sample subjects for all departments and semesters

-- Computer Science Department
INSERT INTO subjects (department, semester, subject_code, name, credits, exam_date) VALUES
-- Semester 1
('Computer Science', 1, 'CS101', 'Programming Basics', 4, '2023-11-15'),
('Computer Science', 1, 'CS102', 'Mathematics I', 3, '2023-11-16'),
('Computer Science', 1, 'CS103', 'Physics I', 3, '2023-11-17'),
('Computer Science', 1, 'CS104', 'Communication Skills', 2, '2023-11-18'),
('Computer Science', 1, 'CS105', 'Computer Fundamentals', 3, '2023-11-19'),
-- Semester 2
('Computer Science', 2, 'CS201', 'Data Structures', 4, '2023-11-20'),
('Computer Science', 2, 'CS202', 'Mathematics II', 3, '2023-11-21'),
('Computer Science', 2, 'CS203', 'Electronics I', 3, '2023-11-22'),
('Computer Science', 2, 'CS204', 'Physics II', 3, '2023-11-23'),
('Computer Science', 2, 'CS205', 'Object-Oriented Programming', 4, '2023-11-24'),
-- Semester 3
('Computer Science', 3, 'CS301', 'Algorithms', 4, '2023-11-25'),
('Computer Science', 3, 'CS302', 'Database Systems', 3, '2023-11-26'),
('Computer Science', 3, 'CS303', 'Operating Systems', 4, '2023-11-27'),
('Computer Science', 3, 'CS304', 'Discrete Mathematics', 3, '2023-11-28'),
('Computer Science', 3, 'CS305', 'Computer Networks', 4, '2023-11-29'),
-- Semester 4
('Computer Science', 4, 'CS401', 'Software Engineering', 4, '2023-12-01'),
('Computer Science', 4, 'CS402', 'Theory of Computation', 4, '2023-12-02'),
('Computer Science', 4, 'CS403', 'Web Development', 3, '2023-12-03'),
('Computer Science', 4, 'CS404', 'Artificial Intelligence', 4, '2023-12-04'),
('Computer Science', 4, 'CS405', 'Mobile Computing', 3, '2023-12-05'),
-- Semester 5
('Computer Science', 5, 'CS501', 'Cloud Computing', 4, '2023-12-06'),
('Computer Science', 5, 'CS502', 'Data Science', 4, '2023-12-07'),
('Computer Science', 5, 'CS503', 'Big Data Analytics', 4, '2023-12-08'),
('Computer Science', 5, 'CS504', 'Internet of Things', 3, '2023-12-09'),
('Computer Science', 5, 'CS505', 'Cyber Security', 4, '2023-12-10'),
-- Semester 6
('Computer Science', 6, 'CS601', 'Compiler Design', 4, '2024-05-01'),
('Computer Science', 6, 'CS602', 'Computer Networks', 4, '2024-05-03'),
('Computer Science', 6, 'CS603', 'Software Project Management', 3, '2024-05-05'),
('Computer Science', 6, 'CS604', 'Machine Learning', 3, '2024-05-07'),
('Computer Science', 6, 'CS605', 'Blockchain Technology', 3, '2024-05-09'),
-- Semester 7
('Computer Science', 7, 'CS701', 'Distributed Systems', 4, '2024-11-01'),
('Computer Science', 7, 'CS702', 'Cyber Law and Ethics', 3, '2024-11-03'),
('Computer Science', 7, 'CS703', 'Internet of Things', 3, '2024-11-05'),
('Computer Science', 7, 'CS704', 'Natural Language Processing', 4, '2024-11-07'),
('Computer Science', 7, 'CS705', 'Cloud Security', 3, '2024-11-09'),
-- Semester 8
('Computer Science', 8, 'CS801', 'Data Mining', 4, '2024-12-01'),
('Computer Science', 8, 'CS802', 'DevOps Engineering', 3, '2024-12-03'),
('Computer Science', 8, 'CS803', 'Advanced Web Technologies', 4, '2024-12-05'),
('Computer Science', 8, 'CS804', 'Big Data Analytics', 4, '2024-12-07'),
('Computer Science', 8, 'CS805', 'Project Work', 6, '2024-12-09');

-- Electronics and Communication Department
INSERT INTO subjects (department, semester, subject_code, name, credits, exam_date) VALUES
-- Semester 1
('Electronics and Communication', 1, 'EC101', 'Circuit Analysis', 4, '2023-11-15'),
('Electronics and Communication', 1, 'EC102', 'Mathematics I', 3, '2023-11-16'),
('Electronics and Communication', 1, 'EC103', 'Physics I', 3, '2023-11-17'),
('Electronics and Communication', 1, 'EC104', 'Digital Logic', 4, '2023-11-18'),
('Electronics and Communication', 1, 'EC105', 'Engineering Drawing', 2, '2023-11-19'),
-- Semester 2
('Electronics and Communication', 2, 'EC201', 'Analog Circuits', 4, '2023-11-22'),
('Electronics and Communication', 2, 'EC202', 'Mathematics II', 3, '2023-11-24'),
('Electronics and Communication', 2, 'EC203', 'Electronics II', 4, '2023-11-26'),
('Electronics and Communication', 2, 'EC204', 'Engineering Mechanics', 3, '2023-11-28'),
('Electronics and Communication', 2, 'EC205', 'Electrical Machines', 3, '2023-11-30'),
-- Semester 3
('Electronics and Communication', 3, 'EC301', 'Digital Electronics', 4, '2023-12-01'),
('Electronics and Communication', 3, 'EC302', 'Signals and Systems', 4, '2023-12-03'),
('Electronics and Communication', 3, 'EC303', 'Microprocessors', 4, '2023-12-05'),
('Electronics and Communication', 3, 'EC304', 'Probability and Statistics', 3, '2023-12-07'),
('Electronics and Communication', 3, 'EC305', 'Electromagnetic Fields', 4, '2023-12-09'),
-- Semester 4
('Electronics and Communication', 4, 'EC401', 'Control Systems', 4, '2023-12-11'),
('Electronics and Communication', 4, 'EC402', 'Analog Communication', 4, '2023-12-13'),
('Electronics and Communication', 4, 'EC403', 'Electromagnetic Waves', 3, '2023-12-15'),
('Electronics and Communication', 4, 'EC404', 'Integrated Circuits', 4, '2023-12-17'),
('Electronics and Communication', 4, 'EC405', 'Microcontrollers', 3, '2023-12-19'),
-- Semester 5
('Electronics and Communication', 5, 'EC501', 'Digital Communication', 4, '2023-12-21'),
('Electronics and Communication', 5, 'EC502', 'VLSI Design', 4, '2023-12-23'),
('Electronics and Communication', 5, 'EC503', 'Embedded Systems', 4, '2023-12-25'),
('Electronics and Communication', 5, 'EC504', 'Antennas and Propagation', 3, '2023-12-27'),
('Electronics and Communication', 5, 'EC505', 'Wireless Communication', 4, '2023-12-29'),
-- Semester 6
('Electronics and Communication', 6, 'EC601', 'Digital Signal Processing', 4, '2024-05-01'),
('Electronics and Communication', 6, 'EC602', 'Communication Systems II', 4, '2024-05-03'),
('Electronics and Communication', 6, 'EC603', 'ASIC Design', 3, '2024-05-05'),
('Electronics and Communication', 6, 'EC604', 'IoT Systems', 3, '2024-05-07'),
('Electronics and Communication', 6, 'EC605', 'Wireless Networks', 3, '2024-05-09'),
-- Semester 7
('Electronics and Communication', 7, 'EC701', 'Wireless Communication', 4, '2024-11-02'),
('Electronics and Communication', 7, 'EC702', 'VLSI Design', 4, '2024-11-04'),
('Electronics and Communication', 7, 'EC703', 'Embedded Systems', 3, '2024-11-06'),
('Electronics and Communication', 7, 'EC704', 'Optical Communication', 3, '2024-11-08'),
('Electronics and Communication', 7, 'EC705', 'IoT Systems', 3, '2024-11-10'),
-- Semester 8
('Electronics and Communication', 8, 'EC801', 'Satellite Communication', 4, '2024-12-02'),
('Electronics and Communication', 8, 'EC802', 'Advanced Embedded Systems', 4, '2024-12-04'),
('Electronics and Communication', 8, 'EC803', 'Nanotechnology', 3, '2024-12-06'),
('Electronics and Communication', 8, 'EC804', 'AI for Communication', 3, '2024-12-08'),
('Electronics and Communication', 8, 'EC805', 'Project Work', 6, '2024-12-10');

-- Electrical Engineering Department
INSERT INTO subjects (department, semester, subject_code, name, credits, exam_date) VALUES
-- Semester 1
('Electrical Engineering', 1, 'EE101', 'Basic Electrical Engineering', 4, '2023-11-15'),
('Electrical Engineering', 1, 'EE102', 'Mathematics I', 3, '2023-11-16'),
('Electrical Engineering', 1, 'EE103', 'Physics I', 3, '2023-11-17'),
('Electrical Engineering', 1, 'EE104', 'Electrical Measurements', 4, '2023-11-18'),
('Electrical Engineering', 1, 'EE105', 'Circuit Analysis', 3, '2023-11-19'),
-- Semester 2
('Electrical Engineering', 2, 'EE201', 'Electrical Machines I', 4, '2023-11-22'),
('Electrical Engineering', 2, 'EE202', 'Mathematics II', 3, '2023-11-24'),
('Electrical Engineering', 2, 'EE203', 'Electronics II', 4, '2023-11-26'),
('Electrical Engineering', 2, 'EE204', 'Power Systems I', 4, '2023-11-28'),
('Electrical Engineering', 2, 'EE205', 'Engineering Mechanics', 3, '2023-11-30'),
-- Semester 3
('Electrical Engineering', 3, 'EE301', 'Electrical Machines II', 4, '2023-12-01'),
('Electrical Engineering', 3, 'EE302', 'Power Systems II', 4, '2023-12-03'),
('Electrical Engineering', 3, 'EE303', 'Control Systems', 4, '2023-12-05'),
('Electrical Engineering', 3, 'EE304', 'Electrical Measurement', 3, '2023-12-07'),
('Electrical Engineering', 3, 'EE305', 'Analog Electronics', 4, '2023-12-09'),
-- Semester 4
('Electrical Engineering', 4, 'EE401', 'Digital Electronics', 4, '2023-12-11'),
('Electrical Engineering', 4, 'EE402', 'Signal Processing', 4, '2023-12-13'),
('Electrical Engineering', 4, 'EE403', 'Power Electronics', 4, '2023-12-15'),
('Electrical Engineering', 4, 'EE404', 'Electrical Drives', 3, '2023-12-17'),
('Electrical Engineering', 4, 'EE405', 'Microcontrollers', 3, '2023-12-19'),
-- Semester 5
('Electrical Engineering', 5, 'EE501', 'Renewable Energy', 4, '2023-12-21'),
('Electrical Engineering', 5, 'EE502', 'High Voltage Engineering', 4, '2023-12-23'),
('Electrical Engineering', 5, 'EE503', 'Power System Protection', 4, '2023-12-25'),
('Electrical Engineering', 5, 'EE504', 'Energy Systems', 3, '2023-12-27'),
('Electrical Engineering', 5, 'EE505', 'Electrical CAD', 4, '2023-12-29'),
-- Semester 6
('Electrical Engineering', 6, 'EE601', 'Power System Operation & Control', 4, '2024-05-01'),
('Electrical Engineering', 6, 'EE602', 'Smart Grid Technology', 4, '2024-05-03'),
('Electrical Engineering', 6, 'EE603', 'Electrical Machine Design', 3, '2024-05-05'),
('Electrical Engineering', 6, 'EE604', 'Embedded Systems', 3, '2024-05-07'),
('Electrical Engineering', 6, 'EE605', 'Energy Management', 3, '2024-05-09'),
-- Semester 7
('Electrical Engineering', 7, 'EE701', 'Smart Grid Technologies', 4, '2024-11-01'),
('Electrical Engineering', 7, 'EE702', 'High Voltage Engineering', 4, '2024-11-03'),
('Electrical Engineering', 7, 'EE703', 'Power Electronics Drives', 3, '2024-11-05'),
('Electrical Engineering', 7, 'EE704', 'Electrical Machine Design', 3, '2024-11-07'),
('Electrical Engineering', 7, 'EE705', 'Electric Vehicles', 3, '2024-11-09'),
-- Semester 8
('Electrical Engineering', 8, 'EE801', 'Power System Operation', 4, '2024-12-01'),
('Electrical Engineering', 8, 'EE802', 'Flexible AC Transmission', 3, '2024-12-03'),
('Electrical Engineering', 8, 'EE803', 'Smart Energy Systems', 3, '2024-12-05'),
('Electrical Engineering', 8, 'EE804', 'Energy Audit & Management', 4, '2024-12-07'),
('Electrical Engineering', 8, 'EE805', 'Project Work', 6, '2024-12-09');

-- Mechanical Engineering Department
INSERT INTO subjects (department, semester, subject_code, name, credits, exam_date) VALUES
-- Semester 1
('Mechanical Engineering', 1, 'ME101', 'Engineering Mechanics', 4, '2023-11-15'),
('Mechanical Engineering', 1, 'ME102', 'Mathematics I', 3, '2023-11-16'),
('Mechanical Engineering', 1, 'ME103', 'Physics I', 3, '2023-11-17'),
('Mechanical Engineering', 1, 'ME104', 'Workshop Practices', 2, '2023-11-18'),
('Mechanical Engineering', 1, 'ME105', 'Introduction to Thermodynamics', 3, '2023-11-19'),
-- Semester 2
('Mechanical Engineering', 2, 'ME201', 'Thermodynamics', 4, '2023-11-21'),
('Mechanical Engineering', 2, 'ME202', 'Mathematics II', 3, '2023-11-23'),
('Mechanical Engineering', 2, 'ME203', 'Strength of Materials', 4, '2023-11-25'),
('Mechanical Engineering', 2, 'ME204', 'Fluid Mechanics', 3, '2023-11-27'),
('Mechanical Engineering', 2, 'ME205', 'Manufacturing Processes', 4, '2023-11-29'),
-- Semester 3
('Mechanical Engineering', 3, 'ME301', 'Dynamics of Machines', 4, '2023-12-01'),
('Mechanical Engineering', 3, 'ME302', 'Heat Transfer', 4, '2023-12-03'),
('Mechanical Engineering', 3, 'ME303', 'Machine Design', 4, '2023-12-05'),
('Mechanical Engineering', 3, 'ME304', 'Material Science', 3, '2023-12-07'),
('Mechanical Engineering', 3, 'ME305', 'Fluid Power Systems', 3, '2023-12-09'),
-- Semester 4
('Mechanical Engineering', 4, 'ME401', 'Automobile Engineering', 4, '2023-12-11'),
('Mechanical Engineering', 4, 'ME402', 'Production Technology', 4, '2023-12-13'),
('Mechanical Engineering', 4, 'ME403', 'Engineering Metallurgy', 4, '2023-12-15'),
('Mechanical Engineering', 4, 'ME404', 'Instrumentation and Control', 3, '2023-12-17'),
('Mechanical Engineering', 4, 'ME405', 'Computational Fluid Dynamics', 4, '2023-12-19'),
-- Semester 5
('Mechanical Engineering', 5, 'ME501', 'Energy Systems', 4, '2023-12-21'),
('Mechanical Engineering', 5, 'ME502', 'Robotics', 4, '2023-12-23'),
('Mechanical Engineering', 5, 'ME503', 'Industrial Engineering', 4, '2023-12-25'),
('Mechanical Engineering', 5, 'ME504', 'Mechatronics', 3, '2023-12-27'),
('Mechanical Engineering', 5, 'ME505', 'Additive Manufacturing', 4, '2023-12-29'),
-- Semester 6
('Mechanical Engineering', 6, 'ME601', 'Heat and Mass Transfer', 4, '2024-05-01'),
('Mechanical Engineering', 6, 'ME602', 'Design of Machine Elements II', 4, '2024-05-03'),
('Mechanical Engineering', 6, 'ME603', 'Finite Element Methods', 3, '2024-05-05'),
('Mechanical Engineering', 6, 'ME604', 'Hydraulic Machines', 3, '2024-05-07'),
('Mechanical Engineering', 6, 'ME605', 'Maintenance Engineering', 3, '2024-05-09'),
-- Semester 7
('Mechanical Engineering', 7, 'ME701', 'Finite Element Analysis', 4, '2024-11-02'),
('Mechanical Engineering', 7, 'ME702', 'Robotics and Automation', 4, '2024-11-04'),
('Mechanical Engineering', 7, 'ME703', 'Mechatronics', 3, '2024-11-06'),
('Mechanical Engineering', 7, 'ME704', 'Industrial Safety', 3, '2024-11-08'),
('Mechanical Engineering', 7, 'ME705', 'Automobile Engineering', 3, '2024-11-10'),
-- Semester 8
('Mechanical Engineering', 8, 'ME801', 'Product Design and Development', 4, '2024-12-02'),
('Mechanical Engineering', 8, 'ME802', 'Energy Conservation', 3, '2024-12-04'),
('Mechanical Engineering', 8, 'ME803', 'Advanced Manufacturing', 4, '2024-12-06'),
('Mechanical Engineering', 8, 'ME804', 'Renewable Energy Systems', 3, '2024-12-08'),
('Mechanical Engineering', 8, 'ME805', 'Project Work', 6, '2024-12-10');

-- Civil Engineering Department
INSERT INTO subjects (department, semester, subject_code, name, credits, exam_date) VALUES
-- Semester 1
('Civil Engineering', 1, 'CE101', 'Engineering Mechanics', 4, '2023-11-15'),
('Civil Engineering', 1, 'CE102', 'Mathematics I', 3, '2023-11-16'),
('Civil Engineering', 1, 'CE103', 'Physics I', 3, '2023-11-17'),
('Civil Engineering', 1, 'CE104', 'Environmental Science', 2, '2023-11-18'),
('Civil Engineering', 1, 'CE105', 'Engineering Drawing', 3, '2023-11-19'),
-- Semester 2
('Civil Engineering', 2, 'CE201', 'Surveying', 4, '2023-11-21'),
('Civil Engineering', 2, 'CE202', 'Mathematics II', 3, '2023-11-23'),
('Civil Engineering', 2, 'CE203', 'Strength of Materials', 4, '2023-11-25'),
('Civil Engineering', 2, 'CE204', 'Fluid Mechanics', 3, '2023-11-27'),
('Civil Engineering', 2, 'CE205', 'Building Materials', 3, '2023-11-29'),
-- Semester 3
('Civil Engineering', 3, 'CE301', 'Structural Analysis I', 4, '2023-12-01'),
('Civil Engineering', 3, 'CE302', 'Geotechnical Engineering', 4, '2023-12-03'),
('Civil Engineering', 3, 'CE303', 'Hydraulics', 4, '2023-12-05'),
('Civil Engineering', 3, 'CE304', 'Concrete Technology', 3, '2023-12-07'),
('Civil Engineering', 3, 'CE305', 'Transportation Engineering I', 3, '2023-12-09'),
-- Semester 4
('Civil Engineering', 4, 'CE401', 'Structural Analysis II', 4, '2023-12-11'),
('Civil Engineering', 4, 'CE402', 'Environmental Engineering', 4, '2023-12-13'),
('Civil Engineering', 4, 'CE403', 'Foundation Engineering', 4, '2023-12-15'),
('Civil Engineering', 4, 'CE404', 'Construction Planning', 3, '2023-12-17'),
('Civil Engineering', 4, 'CE405', 'Remote Sensing', 4, '2023-12-19'),
-- Semester 5
('Civil Engineering', 5, 'CE501', 'Structural Design I', 4, '2023-12-21'),
('Civil Engineering', 5, 'CE502', 'Transportation Engineering II', 4, '2023-12-23'),
('Civil Engineering', 5, 'CE503', 'Irrigation Engineering', 4, '2023-12-25'),
('Civil Engineering', 5, 'CE504', 'Water Resources Engineering', 3, '2023-12-27'),
('Civil Engineering', 5, 'CE505', 'Construction Management', 4, '2023-12-29'),
-- Semester 6
('Civil Engineering', 6, 'CE601', 'Structural Design II', 4, '2024-05-01'),
('Civil Engineering', 6, 'CE602', 'Transportation Engineering III', 4, '2024-05-03'),
('Civil Engineering', 6, 'CE603', 'Advanced Foundation Engineering', 3, '2024-05-05'),
('Civil Engineering', 6, 'CE604', 'Water Supply Engineering', 3, '2024-05-07'),
('Civil Engineering', 6, 'CE605', 'Project Management', 3, '2024-05-09'),
-- Semester 7
('Civil Engineering', 7, 'CE701', 'Structural Dynamics', 4, '2024-11-01'),
('Civil Engineering', 7, 'CE702', 'Construction Project Management', 3, '2024-11-03'),
('Civil Engineering', 7, 'CE703', 'Ground Improvement Techniques', 3, '2024-11-05'),
('Civil Engineering', 7, 'CE704', 'Remote Sensing & GIS', 4, '2024-11-07'),
('Civil Engineering', 7, 'CE705', 'Green Building Technologies', 3, '2024-11-09'),
-- Semester 8
('Civil Engineering', 8, 'CE801', 'Bridge Engineering', 4, '2024-12-01'),
('Civil Engineering', 8, 'CE802', 'Construction Economics', 3, '2024-12-03'),
('Civil Engineering', 8, 'CE803', 'Environmental Impact Assessment', 3, '2024-12-05'),
('Civil Engineering', 8, 'CE804', 'Disaster Management', 3, '2024-12-07'),
('Civil Engineering', 8, 'CE805', 'Project Work', 6, '2024-12-09');