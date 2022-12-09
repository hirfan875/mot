<?php

return [

    'payu' => [

        'pos_id' => env('PAYU_POS_ID', '408758'),
        'client_id' => env('PAYU_CLIENT_ID', '408758'),
        'client_secret' => env('PAYU_CLIENT_SECRET', 'b4ab171fe05cbf04690ca253ef31dbbb'),
        'payment_url' => env('PAYU_PAYMENT_URL', 'https://secure.snd.payu.com/api/v2_1/orders'),
        'authorize_url' => env('PAYU_AUTHORIZE_URL', 'https://secure.snd.payu.com/pl/standard/user/oauth/authorize')
    ],

    'my-fatoorah'=>[
        'token'=> env('MYFATOORAH_TOKEN', 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL'),
        'send-payment-url' => env('MYFATOORAH_SEND_PAYMENT_URL', 'https://apitest.myfatoorah.com/v2/SendPayment'),
        'verify-payment-url' => env('MYFATOORAH_VERIFY_PAYMENT_URL', 'https://apitest.myfatoorah.com/v2/getPaymentStatus'),
        'refund-payment-url' => env('MYFATOORAH_REFUND_PAYMENT_URL', 'https://apitest.myfatoorah.com/v2/MakeRefund'),
    ]
];
