CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR (255) NOT NULL,
    description TEXT,
    end_date DATE,
    status ENUM ('in process', 'done') DEFAULT 'in process'
);