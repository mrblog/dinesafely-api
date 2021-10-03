# Dine Safely API

REST API for the dinesafely.org app.

Built on the Lumen framework.

Uses Google Places API.

## Endpoints:

`GET` `/v1/places/nearby`- search places near a location

`GET` ```/v1/places/find``` - find places with a text query

`POST` `/v1/place/score` - quere a score

`PUT` `/v1/places/score/token/:token` - confirm and post a pending score

`GET` `/v1/cities` - auto-ccomplete city names
