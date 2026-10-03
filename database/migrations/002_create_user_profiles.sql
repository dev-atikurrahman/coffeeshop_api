CREATE TABLE user_profiles (
    user_id           INT UNSIGNED PRIMARY KEY,
    phone_number      VARCHAR(30)  NULL,
    gender            ENUM('MALE','FEMALE','OTHER','UNKNOWN') NOT NULL DEFAULT 'UNKNOWN',
    dob               DATE NULL,
    bio               TEXT NULL,
    profile_image_url VARCHAR(500) NULL,
    hobbies           JSON NULL,
    street            VARCHAR(255) NULL,
    city              VARCHAR(100) NULL,
    zip_code          VARCHAR(20)  NULL,
    updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_profile_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;