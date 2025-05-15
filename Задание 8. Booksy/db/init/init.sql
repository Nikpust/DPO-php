CREATE TABLE IF NOT EXISTS users (
    user_created_at     TIMESTAMP           NOT NULL,
    user_id             SERIAL              PRIMARY KEY,
    user_fullname       VARCHAR(150)        NOT NULL,
    user_email          VARCHAR(255)        NOT NULL UNIQUE,
    user_password       VARCHAR(255)        NOT NULL
);

CREATE TABLE IF NOT EXISTS books (
    book_created_at         TIMESTAMP           NOT NULL,
    book_updated_at         TIMESTAMP           NOT NULL,
    book_id                 SERIAL              PRIMARY KEY,
    book_title              VARCHAR(255)        NOT NULL,
    book_author             VARCHAR(255)        NOT NULL,
    book_cover_path         VARCHAR(255)        NOT NULL,
    book_file_path          VARCHAR(255)        NOT NULL,
    book_read_date          DATE                NOT NULL,
    book_allow_download     INT                 NOT NULL,
    user_id INT NOT NULL REFERENCES users(user_id) ON DELETE CASCADE
);