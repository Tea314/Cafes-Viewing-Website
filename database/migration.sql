CREATE DATABASE cafe_review;
USE cafe_review;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(25) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_admin INT NOT NULL
);
CREATE TABLE IF NOT EXISTS districts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS cafes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    folder_name VARCHAR(255) NOT NULL, 
    description TEXT,
    address VARCHAR(255),
    district_id INT,
    price_range ENUM('Low', 'Medium', 'High'),
    status ENUM('pending', 'approved') DEFAULT 'pending',
    FOREIGN KEY (district_id) REFERENCES districts(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS cafe_tags (
    cafe_id INT,
    tag_id INT,
    PRIMARY KEY (cafe_id, tag_id),
    FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cafe_id INT NOT NULL,
    report_type VARCHAR(50) NOT NULL,
    current_value TEXT NOT NULL,
    proposed_value TEXT NOT NULL,
    timestamp DATETIME NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (cafe_id) REFERENCES cafes(id)
);
CREATE TABLE cafe_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, 
    cafe_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE, 
    FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE CASCADE, 
    UNIQUE KEY unique_like (user_id, cafe_id) 
);
INSERT INTO districts (id, name) VALUES
(1, 'District 1'), (2, 'District 2'), (3, 'District 3'),
(4, 'District 4'), (5, 'District 5'), (6, 'District 6'),
(7, 'District 7'), (8, 'District 8'), (9, 'District 9'),
(10, 'District 10'), (11, 'District 11'), (12, 'District 12'),
(13, 'Binh Thanh District'), (14, 'Go Vap District'),
(15, 'Phu Nhuan District'), (16, 'Tan Binh District'),
(17, 'Tan Phu District'), (18, 'Binh Tan District');
INSERT INTO cafes (name, address, district_id, price_range, description, folder_name, status) VALUES
('Aramour', '7 Le Van Mien', 2, 'High', 'Nature and working vibes.', 'aramour', 'approved'),
('Bamos', '89 Ngo Tat To', 13, 'Medium', '24h studying space.', 'bamos', 'approved'),
('Ca Zone', '96/26 Nguyen Gia Tri', 13, 'Medium', 'A 24h working space for who wants to dive in their...', 'ca_zone', 'approved'),
('Centro Bean', '96 Nguyen Dinh Chieu', 1, 'Medium', 'Cozy place for work.', 'centro_bean', 'approved'),
('Cu Xa 30', '301/6 Ngo Thoi Nhiem', 3, 'Medium', 'Chilling vibe and yapping with friends.', 'cu_xa_30', 'approved'),
('Delab', '193A Hai Ba Trung', 1, 'Medium', 'Beautiful dusk.', 'delab', 'approved'),
('Hai Cai Tay', '54/5 Nguyen Binh Khiem', 1, 'Medium', 'Cozy and vibe working space.', 'hai_cai_tay', 'approved'),
('Ka', '230 Pasteur', 3, 'High', 'Delicious coffee.', 'ka', 'approved'),
('Ngam', '193/19 Nam Ky Khoi Nghia', 3, 'Medium', 'Thinking atmosphere.', 'ngam', 'approved'),
('Nha Cua La', '64/41 Nguyen Hong', 13, 'Low', 'A coffee shop in the morning and wine shop in the ...', 'nha_cua_la', 'approved'),
('Po', '160/27 But Dinh Tuy', 13, 'Medium', 'Old design with vintage vibes.', 'po', 'approved'),
('Sipply', '73 Dinh Cong Trang', 1, 'High', 'A coffee station for who loves tasting coffee.', 'sipply', 'approved'),
('Slow', '39 Le Van Mien', 2, 'High', 'A coffee shop with the vibe showing in the name S...', 'slow', 'approved'),
('Test', 'A test address', 1, 'Medium', 'A test description.', 'test', 'pending'),
('Test2', 'A test address', 1, 'Medium', 'A test description.', 'test2', 'pending'),
('Valleys', '325 Van Thanh Dai Trong', 18, 'High', 'A coffee station with studying space.', 'valleys', 'approved'),
('Yen', '96 Nguyen Dinh Chieu', 1, 'Medium', 'A serene green space.', 'yen', 'approved');

