import sys
import encoder


def addFace(image, key):
    encrypted_face_encoding = encoder.encode(image, key)
    return encrypted_face_encoding


def main():
    image = sys.argv[1]
    key = sys.argv[2]
    print(addFace(image, key))


if __name__ == "__main__":
    main()
