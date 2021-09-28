USE covidscore;

CREATE TABLE IF NOT EXISTS place_score
(
    id             INT          NOT NULL AUTO_INCREMENT,
    user_id        VARCHAR(255) NOT NULL,
    place_id       VARCHAR(255) NOT NULL,
    staff_masks    TINYINT      NOT NULL DEFAULT 0,
    customer_masks TINYINT      NOT NULL DEFAULT 0,
    vaccine        TINYINT      NOT NULL DEFAULT 0,
    rating         INT(2)       NOT NULL DEFAULT 0,
    published      TINYINT      NOT NULL DEFAULT 0,

    PRIMARY KEY (id),
    UNIQUE user_place (user_id, place_id)
);

CREATE TABLE IF NOT EXISTS user
(
    user_id VARCHAR(255) NOT NULL,
    token   VARCHAR(255) NOT NULL,

    PRIMARY KEY (user_id)
);

CREATE TABLE IF NOT EXISTS city
(
    id         INT          NOT NULL AUTO_INCREMENT,
    city       VARCHAR(255) NOT NULL,
    full_city  VARCHAR(255) NOT NULL,
    city_lower VARCHAR(255) NOT NULL,
    lat        FLOAT(10, 6) NOT NULL,
    lng        FLOAT(10, 6) NOT NULL,

    PRIMARY KEY (id)
);
