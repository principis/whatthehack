import face_recognition as facereg
from cryptography.fernet import Fernet
import base64


def encode(face, key):
    if len(key) != 32: return 1
    face_object = facereg.load_image_file(face)
    face_encoding = facereg.face_encodings(face_object)[0]
    face_in_string = ' '.join(map(str, face_encoding))
    face_in_bytes = bytes(face_in_string, encoding='utf-8')
    base64key = base64.urlsafe_b64encode(bytes(key, 'utf-8'))
    cipher_suite = Fernet(base64key)
    cipher_text = cipher_suite.encrypt(face_in_bytes)
    return cipher_text.decode('utf-8')
