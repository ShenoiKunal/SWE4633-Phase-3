CREATE TABLE AuthorizedUsers (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,       
    password VARCHAR(255) NOT NULL,             
    isAdmin Boolean NOT NULL
);

CREATE TABLE Items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,    
    item_name VARCHAR(100) NOT NULL,           
    item_price DECIMAL(10, 2) NOT NULL,   
    item_qty int NOT NULL,
    description TEXT                          
);



    
    
    
    