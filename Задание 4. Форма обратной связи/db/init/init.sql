CREATE TABLE IF NOT EXISTS requests (
    request_id              SERIAL          PRIMARY KEY,
    request_created_at      TIMESTAMP       NOT NULL,
    request_full_name       VARCHAR(50)     NOT NULL,
    request_email           VARCHAR(255)    NOT NULL,
    request_phone_number    VARCHAR(20)     NOT NULL,
    request_comment         TEXT            NOT NULL
);