CREATE DATABASE IF NOT EXISTS piano;
USE piano;

CREATE TABLE mst_students (
  student_id CHAR(7) PRIMARY KEY,
  password VARCHAR(36)
);

INSERT INTO mst_students VALUES
('1234567', 'pass1234'),
('7654321', 'testpass');
