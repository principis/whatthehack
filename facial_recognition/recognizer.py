import face_recognition as facereg
import decoder
import sys
import numpy as np


def checkMatch(face, key, face_encrypted):

    requested_face = facereg.load_image_file(face)
    requested_face_encoding = facereg.face_encodings(requested_face)[0]


    compare = facereg.compare_faces([np.asarray(getFace(face_encrypted, key), dtype=np.float64)], requested_face_encoding)

    if compare[0]:
        return 0
    else:
        return 1


def getConfidence(face, key, face_encrypted):
    requested_face = facereg.load_image_file(face)
    requested_face_encoding = facereg.face_encodings(requested_face)[0]

    match_against_face = facereg.load_image_file(getFace(face_encrypted, key))
    match_against_face_encoding = facereg.face_encodings(match_against_face)[0]

    return facereg.face_distance(requested_face_encoding, match_against_face_encoding)


def getConfidencePlain(req_face, match_face):
    requested_face = facereg.load_image_file(req_face)
    requested_face_encoding = facereg.face_encodings(requested_face)[0]

    match_against_face = facereg.load_image_file(match_face)
    match_against_face_encoding = facereg.face_encodings(match_against_face)[0]

    return facereg.face_distance([requested_face_encoding], match_against_face_encoding)[0]


def checkMatchPlain(req_face, match_face):
    requested_face = facereg.load_image_file(req_face)
    requested_face_encoding = facereg.face_encodings(requested_face)[0]

    match_against_face = facereg.load_image_file(match_face)
    match_against_face_encoding = facereg.face_encodings(match_against_face)[0]

    compare = facereg.compare_faces([match_against_face_encoding], requested_face_encoding)
    return compare[0]


def getFace(face_encrypted, key):
    face_vector = decoder.decode(face_encrypted, key)
    return face_vector


def main():
        face = sys.argv[1]
        key = sys.argv[2]
        face_encrypted = sys.argv[3]
        print(checkMatch(face, key, face_encrypted))

if __name__ == "__main__":
    main()
