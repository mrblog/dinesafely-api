ALTER TABLE place_score ADD COLUMN name            VARCHAR(255) NOT NULL AFTER place_id;
ALTER TABLE place_score ADD COLUMN lat             FLOAT(10, 6) NOT NULL after name;
ALTER TABLE place_score ADD COLUMN lng             FLOAT(10, 6) NOT NULL after lat;

ALTER TABLE pending_score ADD COLUMN name            VARCHAR(255) NOT NULL AFTER place_id;
ALTER TABLE pending_score ADD COLUMN lat             FLOAT(10, 6) NOT NULL after name;
ALTER TABLE pending_score ADD COLUMN lng             FLOAT(10, 6) NOT NULL after lat;
