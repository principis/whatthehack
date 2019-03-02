from cryptography.fernet import Fernet
import base64


def decode(cipher_text, key):
    base64key = base64.urlsafe_b64encode(bytes(key, 'utf-8'))
    bytes_text_encrypted = bytes(cipher_text, 'utf-8')
    cipher_suite = Fernet(base64key)
    face_in_bytes = cipher_suite.decrypt(bytes_text_encrypted)
    face_in_string = face_in_bytes.decode('utf-8')
    face_encoding = str.split(face_in_string, " ")
    return face_encoding

