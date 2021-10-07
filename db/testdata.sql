/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
DROP TABLE IF EXISTS city;
CREATE TABLE `city` (
  `id` int NOT NULL AUTO_INCREMENT,
  `city` varchar(255) NOT NULL,
  `full_city` varchar(255) NOT NULL,
  `city_lower` varchar(255) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28354 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO city (city,full_city,city_lower,lat,lng) VALUES
('Danbury','Danbury, CT','danbury, ct',41.401600,-73.471000),
('Danville', 'Danville, IL', 'danville, il', 40.142300, -87.611400),
('Danville', 'Danville, VA', 'danville, va', 36.583100, -79.408700),
('Danville', 'Danville, CA', 'danville, ca', 37.812100, -121.969800),
('Dana Point', 'Dana Point, CA', 'dana point, ca', 33.473300, -117.696800),
('Dania Beach', 'Dania Beach, FL', 'dania beach, fl', 26.059400, -80.163700),
('Danville', 'Danville, KY', 'danville, ky', 37.641800, -84.777700),
('Danville', 'Danville, IN', 'danville, in', 39.760300, -86.507600),
('Dandridge', 'Dandridge, TN', 'dandridge, tn', 36.028500, -83.430800),
('Dansville', 'Dansville, NY', 'dansville, ny', 42.562500, -77.696800),
('Danville', 'Danville, PA', 'danville, pa', 40.961500, -76.612100),
('Danielson', 'Danielson, CT', 'danielson, ct', 41.808600, -71.885400),
('Dana', 'Dana, NC', 'dana, nc', 35.323900, -82.372200),
('Danville', 'Danville, AR', 'danville, ar', 35.053000, -93.390200),
('Daniels', 'Daniels, WV', 'daniels, wv', 37.724000, -81.126700),
('Danbury', 'Danbury, TX', 'danbury, tx', 29.227400, -95.346100),
('Dane', 'Dane, WI', 'dane, wi', 43.249900, -89.499600),
('Danvers', 'Danvers, IL', 'danvers, il', 40.529900, -89.175100),
('Daniel', 'Daniel, UT', 'daniel, ut', 40.466800, -111.409700),
('Danville', 'Danville, OH', 'danville, oh', 40.447000, -82.260800),
('Danville', 'Danville, IA', 'danville, ia', 40.860000, -91.314600),
('Danville', 'Danville, WV', 'danville, wv', 38.080800, -81.834200),
('Danielsville', 'Danielsville, GA', 'danielsville, ga', 34.123800, -83.220100),
('Dana', 'Dana, IN', 'dana, in', 39.807200, -87.494500),
('Dansville', 'Dansville, MI', 'dansville, mi', 42.555800, -84.303000),
('Dante', 'Dante, VA', 'dante, va', 36.979200, -82.296000),
('Danforth', 'Danforth, IL', 'danforth, il', 40.822000, -87.977800),
('Danube', 'Danube, MN', 'danube, mn', 44.791000, -95.102900),
('Danbury', 'Danbury, IA', 'danbury, ia', 42.236400, -95.721600),
('Dannebrog', 'Dannebrog, NE', 'dannebrog, ne', 41.118600, -98.545600),
('Danville', 'Danville, GA', 'danville, ga', 32.606000, -83.246000),
('Danville', 'Danville, MD', 'danville, md', 39.512400, -78.918400),
('Danbury', 'Danbury, NC', 'danbury, nc', 36.411300, -80.212200),
('Danbury', 'Danbury, WI', 'danbury, wi', 46.008800, -92.377700),
('Dana', 'Dana, IL', 'dana, il', 40.956500, -88.950000),
('Danbury', 'Danbury, NE', 'danbury, ne', 40.037700, -100.405100),
('Daniel', 'Daniel, WY', 'daniel, wy', 42.865800, -110.076700),
('Danvers', 'Danvers, MN', 'danvers, mn', 45.281400, -95.755900),
('Dante', 'Dante, SD', 'dante, sd', 43.039900, -98.185600),
('Danville', 'Danville, WA', 'danville, wa', 48.993800, -118.506800),
('Dana', 'Dana, IA', 'dana, ia', 42.107100, -94.238300),
('Danville', 'Danville, KS', 'danville, ks', 37.286000, -97.892100),
('Danville', 'Danville, MO', 'danville, mo', 38.912500, -91.532300);

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;

DROP TABLE IF EXISTS pending_score;
CREATE TABLE `pending_score` (
  `token` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `user_handle` varchar(255) NOT NULL,
  `place_id` varchar(255) NOT NULL,
  `staff_masks` tinyint NOT NULL DEFAULT '0',
  `customer_masks` tinyint NOT NULL DEFAULT '0',
  `vaccine` tinyint NOT NULL DEFAULT '0',
  `outdoor_seating` tinyint NOT NULL DEFAULT '0',
  `rating` int NOT NULL DEFAULT '0',
  `is_affiliated` tinyint NOT NULL DEFAULT '0',
  `notes` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `pending_score` VALUES ('2cab07ff6cb964ac0b176eb600384efb','bigbilly@emalservice.com','Billy Jack','ChIJPfToY6mMj4ARbbcGtQlQchE',0,0,0,1,1,0,'Fined numerous times. Refused to close.','2021-10-06 15:37:13');
INSERT INTO `pending_score` VALUES ('5198b30727c2d1bd12ff1a473680b821','sj@email.com','Sally Again','ChIJhf0nNIDyj4ARJv44yv8mdqY',0,0,0,1,1,0,'','2021-10-04 18:57:39');
INSERT INTO `pending_score` VALUES ('945666babb3293d6700b298019605975','jjones@email.com','Jane','ChIJuVid5KuMj4AR-Yl_7dIWs4M',1,1,0,1,2,0,'It can get crowded','2021-10-05 01:16:19');
INSERT INTO `pending_score` VALUES ('a118b614fd439aa425c3314993174ae3','bbonds@email.com','Barry','ChIJsf-R07OMj4ARY49JhQdBgww',1,0,0,1,2,0,'','2021-10-01 07:32:49');
INSERT INTO `pending_score` VALUES ('eabd0ea401b70ec543fb0d255aed2413','bobby@email.com','Bobby','ChIJsf-R07OMj4ARY49JhQdBgww',1,0,0,1,-1,0,'','2021-10-01 07:23:02');
INSERT INTO `pending_score` VALUES ('f1d59d902bc54a9416b89a4a5e6d06d3','suzie@email.com','Sue','ChIJsf-R07OMj4ARY49JhQdBgww',1,0,0,1,2,0,'','2021-10-01 07:30:32');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
DROP TABLE IF EXISTS place_score;
CREATE TABLE `place_score` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `user_handle` varchar(255) NOT NULL,
  `place_id` varchar(255) NOT NULL,
  `staff_masks` tinyint NOT NULL DEFAULT '0',
  `customer_masks` tinyint NOT NULL DEFAULT '0',
  `outdoor_seating` tinyint NOT NULL DEFAULT '0',
  `vaccine` tinyint NOT NULL DEFAULT '0',
  `rating` int NOT NULL DEFAULT '0',
  `is_affiliated` tinyint NOT NULL DEFAULT '0',
  `notes` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_place` (`user_id`,`place_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `place_score` VALUES (4,'joe@example.com','Joe Random','ChIJOet126uMj4ARQL_qWEW12Jw',1,1,0,0,2,0,'Bridges is ok','2021-09-15 17:40:00','2021-09-30 19:52:49');
INSERT INTO `place_score` VALUES (5,'betty@example.com','Betty Borstein','ChIJp2fEzamMj4ARliL8eEwHKYo',0,0,1,0,2,0,'The Peasant & The Pear do an ok job','2021-09-18 14:50:00','2021-09-30 19:52:49');
INSERT INTO `place_score` VALUES (6,'jerry@example.com','Jerry Jackman','ChIJz2rJsKmMj4AR-gtLy4UsnH0',0,0,1,0,1,0,'Revel Kitchen & Bar too crowded','2021-09-28 19:00:00','2021-09-30 19:52:49');
INSERT INTO `place_score` VALUES (7,'ddr@drake.com','Drake','ChIJsf-R07OMj4ARY49JhQdBgww',1,0,1,0,2,0,'','2021-10-01 06:10:33','2021-10-01 06:24:10');
INSERT INTO `place_score` VALUES (8,'david@bdt.com','David B','ChIJKfDOzqmMj4ARvBZlUVWRpGA',1,0,1,0,2,0,'It\'s a little crowded but generally decent outdoor spacing','2021-10-01 19:46:52','2021-10-01 19:48:52');
