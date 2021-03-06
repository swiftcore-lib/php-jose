<?php
namespace Swiftcore\Jose\Tests\RFC7520;

use Swiftcore\Jose\Tests\TestCase;

abstract class RFC7520 extends TestCase
{
    protected $rfc7520RSAPrivateKey;
    protected $rfc7520RSAPublicKey;
    protected $rfc7520SymmetricKeyMAC;
    protected $rfc7520SymmetricKeyEncryption;
    protected $rfc7520ECPrivateKey;
    protected $rfc7520ECPublicKey;

    public function setUp()
    {
        parent::setUp();

        /*
         * https://tools.ietf.org/html/rfc7520#section-3.4
         */
        $this->rfc7520RSAPrivateKey = [
            "kty" => "RSA",
            "kid" => "bilbo.baggins@hobbiton.example",
            "use" => "sig",
            "n" =>
                "n4EPtAOCc9AlkeQHPzHStgAbgs7bTZLwUBZdR8_KuKPEHLd4rHVTeT
                 -O-XV2jRojdNhxJWTDvNd7nqQ0VEiZQHz_AJmSCpMaJMRBSFKrKb2wqV
                 wGU_NsYOYL-QtiWN2lbzcEe6XC0dApr5ydQLrHqkHHig3RBordaZ6Aj-
                 oBHqFEHYpPe7Tpe-OfVfHd1E6cS6M1FZcD1NNLYD5lFHpPI9bTwJlsde
                 3uhGqC0ZCuEHg8lhzwOHrtIQbS0FVbb9k3-tVTU4fg_3L_vniUFAKwuC
                 LqKnS2BYwdq_mzSnbLY7h_qixoR7jig3__kRhuaxwUkRz5iaiQkqgc5g
                 HdrNP5zw",
            "e" =>
                "AQAB",
            "d" =>
                "bWUC9B-EFRIo8kpGfh0ZuyGPvMNKvYWNtB_ikiH9k20eT-O1q_I78e
                 iZkpXxXQ0UTEs2LsNRS-8uJbvQ-A1irkwMSMkK1J3XTGgdrhCku9gRld
                 Y7sNA_AKZGh-Q661_42rINLRCe8W-nZ34ui_qOfkLnK9QWDDqpaIsA-b
                 MwWWSDFu2MUBYwkHTMEzLYGqOe04noqeq1hExBTHBOBdkMXiuFhUq1BU
                 6l-DqEiWxqg82sXt2h-LMnT3046AOYJoRioz75tSUQfGCshWTBnP5uDj
                 d18kKhyv07lhfSJdrPdM5Plyl21hsFf4L_mHCuoFau7gdsPfHPxxjVOc
                 OpBrQzwQ",
            "p" =>
                "3Slxg_DwTXJcb6095RoXygQCAZ5RnAvZlno1yhHtnUex_fp7AZ_9nR
                 aO7HX_-SFfGQeutao2TDjDAWU4Vupk8rw9JR0AzZ0N2fvuIAmr_WCsmG
                 peNqQnev1T7IyEsnh8UMt-n5CafhkikzhEsrmndH6LxOrvRJlsPp6Zv8
                 bUq0k",
            "q" =>
                "uKE2dh-cTf6ERF4k4e_jy78GfPYUIaUyoSSJuBzp3Cubk3OCqs6grT
                 8bR_cu0Dm1MZwWmtdqDyI95HrUeq3MP15vMMON8lHTeZu2lmKvwqW7an
                 V5UzhM1iZ7z4yMkuUwFWoBvyY898EXvRD-hdqRxHlSqAZ192zB3pVFJ0
                 s7pFc",
            "dmp1" =>
                "B8PVvXkvJrj2L-GYQ7v3y9r6Kw5g9SahXBwsWUzp19TVlgI-YV85q
                 1NIb1rxQtD-IsXXR3-TanevuRPRt5OBOdiMGQp8pbt26gljYfKU_E9xn
                 -RULHz0-ed9E9gXLKD4VGngpz-PfQ_q29pk5xWHoJp009Qf1HvChixRX
                 59ehik",
            "dmq1" =>
                "CLDmDGduhylc9o7r84rEUVn7pzQ6PF83Y-iBZx5NT-TpnOZKF1pEr
                 AMVeKzFEl41DlHHqqBLSM0W1sOFbwTxYWZDm6sI6og5iTbwQGIC3gnJK
                 bi_7k_vJgGHwHxgPaX2PnvP-zyEkDERuf-ry4c_Z11Cq9AqC2yeL6kdK
                 T1cYF8",
            "iqmp" =>
                "3PiqvXQN0zwMeE-sBvZgi289XP9XCQF3VWqPzMKnIgQp7_Tugo6-N
                 ZBKCQsMf3HaEGBjTVJs_jcK8-TRXvaKe-7ZMaQj8VfBdYkssbu0NKDDh
                 jJ-GtiseaDVWt7dcH0cfwxgFUHpQh7FoCrjFJ6h6ZEpMF6xmujs4qMpP
                 z8aaI4"
        ];

        $this->rfc7520RSAPublicKey = [
            "kty" => $this->rfc7520RSAPrivateKey['kty'],
            "kid" => $this->rfc7520RSAPrivateKey['kid'],
            "use" => $this->rfc7520RSAPrivateKey['use'],
            "n" => $this->rfc7520RSAPrivateKey['n'],
            "e" => $this->rfc7520RSAPrivateKey['e'],
        ];

        $this->rfc7520SymmetricKeyMAC = [
            "kty" => "oct",
             "kid" => "018c0ae5-4d9b-471b-bfd6-eef314bc7037",
             "use" => "sig",
             "alg" => "HS256",
             "k" => "hJtXIZ2uSN5kbQfbtTNWbpdmhkV8FJG-Onbc6mxCcYg"
        ];

        $this->rfc7520SymmetricKeyEncryption = [
            "kty" => "oct",
            "kid" => "1e571774-2e08-40da-8308-e8d68773842d",
            "use" => "enc",
            "alg" => "A256GCM",
            "k" => "AAPapAv4LbFbiVawEjagUBluYqN5rhna-8nuldDvOx8"
        ];

        $this->rfc7520ECPrivateKey = [
            "kty" => "EC",
            "kid" => "bilbo.baggins@hobbiton.example",
            "use" => "sig",
            "crv" => "P-521",
            "x" => "AHKZLLOsCOzz5cY97ewNUajB957y-C-U88c3v13nmGZx6sYl_oJXu9A5RkTKqjqvjyekWF-7ytDyRXYgCF5cj0Kt",
            "y" => "AdymlHvOiLxXkEhayXQnNCvDX4h9htZaCJN34kfmC6pV5OhQHiraVySsUdaQkAgDPrwQrJmbnX9cwlGfP-HqHZR1",
            "d" => "AAhRON2r9cqXX1hg-RoI6R1tX5p2rUAYdmpHZoC1XNM56KtscrX6zbKipQrCW9CGZH3T4ubpnoTKLDYJ_fF3_rJt"
        ];

        $this->rfc7520ECPublicKey = [
            "kty" => $this->rfc7520ECPrivateKey['kty'],
            "kid" => $this->rfc7520ECPrivateKey['kid'],
            "use" => $this->rfc7520ECPrivateKey['use'],
            "crv" => $this->rfc7520ECPrivateKey['crv'],
            "x" => $this->rfc7520ECPrivateKey['x'],
            "y" => $this->rfc7520ECPrivateKey['y'],
        ];
    }
}
