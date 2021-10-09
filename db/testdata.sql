/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `id` int NOT NULL AUTO_INCREMENT,
  `city` varchar(255) NOT NULL,
  `full_city` varchar(255) NOT NULL,
  `city_lower` varchar(255) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28397 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `city` VALUES (28354,'Danbury','Danbury, CT','danbury, ct',41.401600,-73.471001);
INSERT INTO `city` VALUES (28355,'Danville','Danville, IL','danville, il',40.142300,-87.611397);
INSERT INTO `city` VALUES (28356,'Danville','Danville, VA','danville, va',36.583099,-79.408699);
INSERT INTO `city` VALUES (28357,'Danville','Danville, CA','danville, ca',37.812099,-121.969803);
INSERT INTO `city` VALUES (28358,'Dana Point','Dana Point, CA','dana point, ca',33.473301,-117.696800);
INSERT INTO `city` VALUES (28359,'Dania Beach','Dania Beach, FL','dania beach, fl',26.059401,-80.163696);
INSERT INTO `city` VALUES (28360,'Danville','Danville, KY','danville, ky',37.641800,-84.777702);
INSERT INTO `city` VALUES (28361,'Danville','Danville, IN','danville, in',39.760300,-86.507599);
INSERT INTO `city` VALUES (28362,'Dandridge','Dandridge, TN','dandridge, tn',36.028500,-83.430801);
INSERT INTO `city` VALUES (28363,'Dansville','Dansville, NY','dansville, ny',42.562500,-77.696800);
INSERT INTO `city` VALUES (28364,'Danville','Danville, PA','danville, pa',40.961498,-76.612099);
INSERT INTO `city` VALUES (28365,'Danielson','Danielson, CT','danielson, ct',41.808601,-71.885399);
INSERT INTO `city` VALUES (28366,'Dana','Dana, NC','dana, nc',35.323898,-82.372200);
INSERT INTO `city` VALUES (28367,'Danville','Danville, AR','danville, ar',35.053001,-93.390198);
INSERT INTO `city` VALUES (28368,'Daniels','Daniels, WV','daniels, wv',37.723999,-81.126701);
INSERT INTO `city` VALUES (28369,'Danbury','Danbury, TX','danbury, tx',29.227400,-95.346100);
INSERT INTO `city` VALUES (28370,'Dane','Dane, WI','dane, wi',43.249901,-89.499603);
INSERT INTO `city` VALUES (28371,'Danvers','Danvers, IL','danvers, il',40.529900,-89.175102);
INSERT INTO `city` VALUES (28372,'Daniel','Daniel, UT','daniel, ut',40.466801,-111.409698);
INSERT INTO `city` VALUES (28373,'Danville','Danville, OH','danville, oh',40.446999,-82.260803);
INSERT INTO `city` VALUES (28374,'Danville','Danville, IA','danville, ia',40.860001,-91.314598);
INSERT INTO `city` VALUES (28375,'Danville','Danville, WV','danville, wv',38.080799,-81.834198);
INSERT INTO `city` VALUES (28376,'Danielsville','Danielsville, GA','danielsville, ga',34.123798,-83.220100);
INSERT INTO `city` VALUES (28377,'Dana','Dana, IN','dana, in',39.807201,-87.494499);
INSERT INTO `city` VALUES (28378,'Dansville','Dansville, MI','dansville, mi',42.555801,-84.303001);
INSERT INTO `city` VALUES (28379,'Dante','Dante, VA','dante, va',36.979198,-82.295998);
INSERT INTO `city` VALUES (28380,'Danforth','Danforth, IL','danforth, il',40.821999,-87.977798);
INSERT INTO `city` VALUES (28381,'Danube','Danube, MN','danube, mn',44.791000,-95.102898);
INSERT INTO `city` VALUES (28382,'Danbury','Danbury, IA','danbury, ia',42.236401,-95.721603);
INSERT INTO `city` VALUES (28383,'Dannebrog','Dannebrog, NE','dannebrog, ne',41.118599,-98.545601);
INSERT INTO `city` VALUES (28384,'Danville','Danville, GA','danville, ga',32.605999,-83.246002);
INSERT INTO `city` VALUES (28385,'Danville','Danville, MD','danville, md',39.512402,-78.918404);
INSERT INTO `city` VALUES (28386,'Danbury','Danbury, NC','danbury, nc',36.411301,-80.212196);
INSERT INTO `city` VALUES (28387,'Danbury','Danbury, WI','danbury, wi',46.008801,-92.377701);
INSERT INTO `city` VALUES (28388,'Dana','Dana, IL','dana, il',40.956501,-88.949997);
INSERT INTO `city` VALUES (28389,'Danbury','Danbury, NE','danbury, ne',40.037701,-100.405098);
INSERT INTO `city` VALUES (28390,'Daniel','Daniel, WY','daniel, wy',42.865799,-110.076698);
INSERT INTO `city` VALUES (28391,'Danvers','Danvers, MN','danvers, mn',45.281399,-95.755898);
INSERT INTO `city` VALUES (28392,'Dante','Dante, SD','dante, sd',43.039902,-98.185600);
INSERT INTO `city` VALUES (28393,'Danville','Danville, WA','danville, wa',48.993801,-118.506798);
INSERT INTO `city` VALUES (28394,'Dana','Dana, IA','dana, ia',42.107101,-94.238297);
INSERT INTO `city` VALUES (28395,'Danville','Danville, KS','danville, ks',37.285999,-97.892097);
INSERT INTO `city` VALUES (28396,'Danville','Danville, MO','danville, mo',38.912498,-91.532303);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
DROP TABLE IF EXISTS `pending_score`;
CREATE TABLE `pending_score` (
  `token` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `user_handle` varchar(255) NOT NULL,
  `place_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
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
INSERT INTO `pending_score` VALUES ('2cab07ff6cb964ac0b176eb600384efb','bigbilly@emalservice.com','Billy Jack','ChIJPfToY6mMj4ARbbcGtQlQchE','Incontro Ristorante',37.820816,-121.998833,0,0,0,1,1,0,'Fined numerous times. Refused to close.','2021-10-06 15:37:13');
INSERT INTO `pending_score` VALUES ('5198b30727c2d1bd12ff1a473680b821','sj@email.com','Sally Again','ChIJhf0nNIDyj4ARJv44yv8mdqY','Firehouse No. 37',37.763885,-121.952621,0,0,0,1,1,0,'','2021-10-04 18:57:39');
INSERT INTO `pending_score` VALUES ('945666babb3293d6700b298019605975','jjones@email.com','Jane','ChIJuVid5KuMj4AR-Yl_7dIWs4M','Yo\'s On Hartz',37.820282,-121.998085,1,1,0,1,2,0,'It can get crowded','2021-10-05 01:16:19');
INSERT INTO `pending_score` VALUES ('a118b614fd439aa425c3314993174ae3','bbonds@email.com','Barry','ChIJsf-R07OMj4ARY49JhQdBgww','Luna Loca',37.813412,-121.996758,1,0,0,1,2,0,'','2021-10-01 07:32:49');
INSERT INTO `pending_score` VALUES ('eabd0ea401b70ec543fb0d255aed2413','bobby@email.com','Bobby','ChIJsf-R07OMj4ARY49JhQdBgww','Luna Loca',37.813412,-121.996758,1,0,0,1,-1,0,'','2021-10-01 07:23:02');
INSERT INTO `pending_score` VALUES ('f1d59d902bc54a9416b89a4a5e6d06d3','suzie@email.com','Sue','ChIJsf-R07OMj4ARY49JhQdBgww','Luna Loca',37.813412,-121.996758,1,0,0,1,2,0,'','2021-10-01 07:30:32');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
DROP TABLE IF EXISTS `place_score`;
CREATE TABLE `place_score` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `user_handle` varchar(255) NOT NULL,
  `place_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
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
INSERT INTO `place_score` VALUES (4,'joe@example.com','Joe Random','ChIJOet126uMj4ARQL_qWEW12Jw','Bridges',37.821117,-121.997940,1,1,0,0,2,0,'Bridges is ok','2021-09-15 17:40:00','2021-09-30 19:52:49');
INSERT INTO `place_score` VALUES (5,'betty@example.com','Betty Borstein','ChIJp2fEzamMj4ARliL8eEwHKYo','The Peasant & The Pear',37.821129,-122.000305,0,0,1,0,2,0,'The Peasant & The Pear do an ok job','2021-09-18 14:50:00','2021-09-30 19:52:49');
INSERT INTO `place_score` VALUES (6,'jerry@example.com','Jerry Jackman','ChIJz2rJsKmMj4AR-gtLy4UsnH0','Revel Kitchen & Bar',37.822014,-122.000694,0,0,1,0,1,0,'Revel Kitchen & Bar too crowded','2021-09-28 19:00:00','2021-09-30 19:52:49');
INSERT INTO `place_score` VALUES (7,'ddr@drake.com','Drake','ChIJsf-R07OMj4ARY49JhQdBgww','Luna Loca',37.813412,-121.996758,1,0,1,0,2,0,'','2021-10-01 06:10:33','2021-10-01 06:24:10');
INSERT INTO `place_score` VALUES (8,'david@bdt.com','David B','ChIJKfDOzqmMj4ARvBZlUVWRpGA','Primo\'s Pizzeria & Pub',37.822906,-122.000671,1,0,1,0,2,0,'It\'s a little crowded but generally decent outdoor spacing','2021-10-01 19:46:52','2021-10-01 19:48:52');
