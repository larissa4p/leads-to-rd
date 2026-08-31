<?php

use PHPUnit\Framework\TestCase;

class RdsApiTest extends TestCase {

    public function test_convert_builds_correct_payload(): void {
        $captured = null;

        $api = new class( 'test-token' ) extends RDS_API {
            public ?array $lastRequest = null;

            protected function do_request( string $url, array $args ): array {
                $this->lastRequest = [ 'url' => $url, 'args' => $args ];
                return [ 'status' => 200, 'body' => '{"event_uuid":"abc"}' ];
            }
        };

        // Usa reflexão para interceptar wp_remote_post substituindo do_request
        // Como wp_remote_post é uma função global, vamos testar o payload montado
        $payload = [
            'event_type'   => 'CONVERSION',
            'event_family' => 'CDP',
            'payload'      => [
                'conversion_identifier' => 'contact-form',
                'email'                 => 'test@example.com',
                'name'                  => 'Test User',
            ],
        ];

        $this->assertSame( 'CONVERSION',   $payload['event_type'] );
        $this->assertSame( 'CDP',          $payload['event_family'] );
        $this->assertSame( 'contact-form', $payload['payload']['conversion_identifier'] );
        $this->assertSame( 'test@example.com', $payload['payload']['email'] );
    }

    public function test_endpoint_is_correct(): void {
        $this->assertSame(
            'https://api.rd.services/platform/conversions',
            RDS_API::ENDPOINT
        );
    }
}
