# Word of the day backend

[wordoftheday.herokuapp.com](wordoftheday.herokuapp.com)

## Authentication

/oauth

### POST /api

Authentication by token. Send auth=test

## GET /api/words

Retreive all words.

## GET /api/words/{:word}

Retreive word by word id.

## PUT /api/words

Store new word. Accepting word(string), answer(string), test_id(integer)

## DELETE /api/words/{:word}

Delete a word by word id

## GET /api/users

Retreive all users

## GET /api/users/{:user}

Retreive user by user id

## PUT/PATCH /api/users/{:user}

Update user (username=string, password=string, balance=integer)

## DELETE /api/users/{:user}

Delete user by id

## GET /api/categories

Retreive all categories.

## GET /api/categories/{:category}

Retrieve category by category id

## POST /api/test/start

### Send 

- category=id
- user=id

### Receive

- status
- balance
- cards

## POST /api/test/result

### Send

User=id

### Receive

- status
- balance

## POST /api/user/{user_id}/addlife

### Receive

- status
- balance