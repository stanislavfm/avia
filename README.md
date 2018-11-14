Test Laravel Project
# Flight Searcher API

## Token
Allows you to operate such entities as Airport, Transporter and Flight.\
Entity structure:
* token: string
* permissions: array
* expires_at: string

### Create Token
* Method: ```POST```
* URL: ```/api/token```

Parameters:
* permissions: array, available values: ```get```, ```create```, ```update```, ```delete```

Response example:
```json
{
  "request": {
    "method": "create",
    "command": "token",
    "parameters": {
      "permsissions": ["get","create","update"]
    }
  },
  "response": {
    "permsissions": ["get","create","update"],
    "expires_at": "2019-11-14 19:34:02",
    "token": "V6HoRqp5XS8VSCigQVjFkDC8dhC7rBlpgWTK541kJkOHaDNhFl0PjQEXcQo9kJGf",
  },
  "version": 1,
  "hash": "X23m8kCMi3iXtl0jftZX08PWBqaAxy1U"
}
```

You should use ```token``` for execution further commands.

### Get Token
* Method: ```GET```
* URL: ```/api/token```

Parameters:
* token: string

Response example:
```json
{
  "request": {
    "method": "get",
    "command": "token",
    "parameters": {
      "token": "V6HoRqp5XS8VSCigQVjFkDC8dhC7rBlpgWTK541kJkOHaDNhFl0PjQEXcQo9kJGf"
    }
  },
  "response": {
    "permsissions": ["get","create","update"],
    "expires_at": "2019-11-14 19:34:02"
  },
  "version": 1,
  "hash": "X23m8kCMi3iXtl0jftZX08PWBqaAxy1U"
}
```

### Update Token
* Method: ```PUT```
* URL: ```/api/token```

Parameters:
* token: string
* permissions: array, available values: ```get```, ```create```, ```update```, ```delete```

Response example:
```json
{
  "request": {
    "method": "update",
    "command": "token",
    "parameters": {
      "token": "V6HoRqp5XS8VSCigQVjFkDC8dhC7rBlpgWTK541kJkOHaDNhFl0PjQEXcQo9kJGf",
      "permsissions": ["get"]
    }
  },
  "response": {
    "permsissions": ["get"],
    "expires_at": "2019-11-14 19:34:02"
  },
  "version": 1,
  "hash": "X23m8kCMi3iXtl0jftZX08PWBqaAxy1U"
}
```

### Delete Token
* Method: ```DELETE```
* URL: ```/api/token```

Parameters:
* token: string

Response example:
```json
{
  "request": {
    "method": "delete",
    "command": "token",
    "parameters": {
      "token": "V6HoRqp5XS8VSCigQVjFkDC8dhC7rBlpgWTK541kJkOHaDNhFl0PjQEXcQo9kJGf",
    }
  },
  "response": {
  },
  "version": 1,
  "hash": "X23m8kCMi3iXtl0jftZX08PWBqaAxy1U"
}
```
