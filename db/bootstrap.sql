USE covidscore;

CREATE TABLE IF NOT EXISTS place_score
(
    id              INT          NOT NULL AUTO_INCREMENT,
    user_id         VARCHAR(255) NOT NULL,
    place_id        VARCHAR(255) NOT NULL,
    staff_masks     TINYINT NOT NULL DEFAULT 0,
    customer_masks  TINYINT NOT NULL DEFAULT 0,
    vaccine         TINYINT NOT NULL DEFAULT 0,
    rating          INT(2)  NOT NULL DEFAULT 0,

    PRIMARY KEY (id),
    UNIQUE user_place (user_id, place_id)
);

CREATE TABLE IF NOT EXISTS pending_score
(
    id              INT          NOT NULL AUTO_INCREMENT,
    user_id         VARCHAR(255) NOT NULL,
    place_id        VARCHAR(255) NOT NULL,
    token           VARCHAR(255) NOT NULL,
    staff_masks     TINYINT NOT NULL DEFAULT 0,
    customer_masks  TINYINT NOT NULL DEFAULT 0,
    vaccine         TINYINT NOT NULL DEFAULT 0,
    rating          INT(2)  NOT NULL DEFAULT 0,

    PRIMARY KEY (id),
    UNIQUE user_place (user_id, place_id)
);
