## OpenSSL Command

JWA documentation ([RFC 7518](https://tools.ietf.org/html/rfc7518)) is very specific but OpenSSL documentation seems not. Naming convention looks to be slightly different sometimes for the same thing between JWA and OpenSSL:

### EC-curves Keys

JWA ES256 (ECDSA using P-256 curve and SHA-256 hash algorithm) would be named curve secp256k1: SECG curve over a 256 bit prime field in OpenSSL
Example: 

    openssl ecparam -out es256.pem -name secp256k1 -genkey

JWA ES384 (ECDSA using P-384 curve and SHA-384 hash algorithm) would be named curve secp384r1: 
NIST/SECG curve over a 384 bit prime field in OpenSSL
Example: 

	openssl ecparam -out es384.pem -name secp384r1 -genkey

JWA ES512 (ECDSA using P-521 curve and SHA-512 hash algorithm) would be named curve secp521r1: 
NIST/SECG curve over a 521 bit prime field in OpenSSL
Example: 

    openssl ecparam -out es521.pem -name secp521r1 -genkey

### RSA Keys

RS256 (RSASSA using SHA-256 hash algorithm) 
Example: 

    openssl genrsa -out rs256.pem 256

RS384 (RSASSA using SHA-384 hash algorithm)
Example: 

    openssl genrsa -out rs384.pem 384

RS512 (RSASSA using SHA-512 hash algorithm)
Example: 

    openssl genrsa -out rs512.pem 512

### HMAC keys

    openssl rand -<base64|hex> <256|384|512>

Where length corresponds to which HMAC I am using. I read that the recommendation is to have the keys be the same site at the block size used for hashing.

Example (for HMAC 512) key generation: 

    openssl rand -base64 512
    
To get a base64 encoded key.