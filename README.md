FORMAT: 1A

# ORSATMAX API

# Authenticate [/authenticate]
API Authentication Layer.

## Authenticate user by email/password [POST /authenticate/authenticate]
Get a token.

+ Request (application/json)
    + Headers

            Accept: application/vnd.orsatmax.v1+json
    + Body

            {
                "email": "abc@def.com",
                "password": "p@55w0rd"
            }

+ Response 401 (application/json)
    + Body

            {
                "error": "invalid_credentials"
            }

+ Response 500 (application/json)
    + Body

            {
                "error": "could_not_create_token"
            }

+ Response 200 (application/json)
    + Body

            {
                "token": "jwt_generated_token"
            }

# Airs [/airs]
Air resource representation.

## Show all airs [GET /airs]
Get a JSON representation of all the airs.

# Site [/sites]
Site resource representation.

## Show all sites [GET /sites]
Get a JSON representation of all the sites.

+ Request (application/json)
    + Headers

            Accept: application/vnd.orsatmax.v1+json
            x-show-site-fields: formal_name,short_name
