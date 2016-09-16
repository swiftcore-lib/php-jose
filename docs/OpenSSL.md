## OpenSSL Key Generation Command

JWA documentation ([RFC 7518](https://tools.ietf.org/html/rfc7518)) is very specific but OpenSSL documentation seems not. Naming convention looks to be slightly different sometimes for the same thing between JWA and OpenSSL:

### EC-curves Keys

JWA ES256 (ECDSA using P-256 curve and SHA-256 hash algorithm) would be `secp256k1`, SECG curve over a 256 bit prime field in OpenSSL:

    openssl ecparam -out es256.pem -name secp256k1 -genkey

JWA ES384 (ECDSA using P-384 curve and SHA-384 hash algorithm) would be `secp384r1`, NIST/SECG curve over a 384 bit prime field in OpenSSL:

	openssl ecparam -out es384.pem -name secp384r1 -genkey

JWA ES512 (ECDSA using P-521 curve and SHA-512 hash algorithm) would be `secp521r1`, NIST/SECG curve over a 521 bit prime field in OpenSSL:

    openssl ecparam -out es521.pem -name secp521r1 -genkey

### RSA Keys

RS256 (RSASSA using SHA-256 hash algorithm): 

    openssl genrsa -out rs256.pem 256

RS384 (RSASSA using SHA-384 hash algorithm):

    openssl genrsa -out rs384.pem 384

RS512 (RSASSA using SHA-512 hash algorithm):

    openssl genrsa -out rs512.pem 512

### HMAC keys

    openssl rand -<base64|hex> <256|384|512>

Where length corresponds to which HMAC should be used. The recommendation is to have the keys to be the same size than the block size used for hashing:

    openssl rand -base64 512


## OpenSSL ECDSA Signing and Verifying

OpenSSL ECDSA signature is a bit different than what is defined in JWA as well.

> (Note that the Integer-to-OctetString Conversion defined in Section 2.3.7 of SEC1 used to represent R and S as octet sequences adds zero-valued high-order padding bits when needed to round the size up to a multiple of 8 bits; thus, each 521-bit integer is represented using 528 bits in 66 octets.)

While OpenSSL output the signature in sequence:

```
ECDSASignature ::= SEQUENCE {
  R    INTEGER
  S    INTEGER
}
```
And neither R nor S is padded intoto 528 bits or 66 octets.
