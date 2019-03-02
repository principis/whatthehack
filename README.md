# Whatthepay repository
This is the repository for whatthepay, our prototype for the WhatTheHack 2019 Hackathon.

## Requirements
* dlib
* face_recognition
* cryptography

## Python backend
### recognizer.py face key face_encrypted
Recognizer.py is the script that returns whether a scanned face matches 
the scanned card. 
face is the scanned face, a path to the image file should be given
key is the key that is used for the encryption, it is stored on the card
face_encrypted is the encrypted face vector that is retrieved in the 
database matching the userID

### addFace.py
addFace contains the addFace(image, key) function that returns an 
encrypted vector version of the image. 

### encoder.py
encoder contains the encode(face, key) function that returns an 
encrypted version of the face in a string. The face parameter should be 
the path to the scanned image file. The image will be encrypted using 
key as the encryption key. This key needs to be *exactly 32 bytes* long.

### decoder.py
decoder contains the decode(cipher_text, key) function that returns a 
face vector. Cipher_text is the face vector encrypted and cast to a 
string, key is the key that will be used for decoding.


