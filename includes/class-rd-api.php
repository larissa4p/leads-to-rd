<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class RDS_API {

    const ENDPOINT = 'https://api.rd.services/platform/conversions';

    private string $token;

    public function __construct( string $token ) {
        $this->token = $token;
    }

    /**
     * Envia uma conversão para a RD Station.
     *
     * @param string $identifier  Nome do evento/formulário.
     * @param array  $contact     Campos do contato: email (obrigatório), name, phone, etc.
     * @return array{ success: bool, status: int, body: mixed }
     */
    public function convert( string $identifier, array $contact ): array {
        $payload = [
            'event_type'   => 'CONVERSION',
            'event_family' => 'CDP',
            'payload'      => array_merge(
                [ 'conversion_identifier' => $identifier ],
                $contact
            ),
        ];

        $response = wp_remote_post( self::ENDPOINT, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type'  => 'application/json',
            ],
            'body'    => wp_json_encode( $payload ),
            'timeout' => 15,
        ] );

        if ( is_wp_error( $response ) ) {
            return [ 'success' => false, 'status' => 0, 'body' => $response->get_error_message() ];
        }

        $status = wp_remote_retrieve_response_code( $response );
        $body   = json_decode( wp_remote_retrieve_body( $response ), true );

        return [
            'success' => $status >= 200 && $status < 300,
            'status'  => $status,
            'body'    => $body,
        ];
    }
}
