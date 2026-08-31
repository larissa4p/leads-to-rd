<?php

use PHPUnit\Framework\TestCase;

class RdsWPFormsTest extends TestCase {

    private RDS_WPForms_Testable $wpf;

    protected function setUp(): void {
        $this->wpf = new RDS_WPForms_Testable();
    }

    public function test_extracts_email_field(): void {
        $fields = [
            [ 'type' => 'email', 'value' => 'user@example.com', 'name' => 'E-mail' ],
        ];
        $contact = $this->wpf->extract_contact( $fields );
        $this->assertSame( 'user@example.com', $contact['email'] );
    }

    public function test_extracts_name_field(): void {
        $fields = [
            [ 'type' => 'email', 'value' => 'user@example.com', 'name' => 'E-mail' ],
            [ 'type' => 'text',  'value' => 'João Lima',         'name' => 'Nome' ],
        ];
        $contact = $this->wpf->extract_contact( $fields );
        $this->assertSame( 'João Lima', $contact['name'] );
    }

    public function test_extracts_phone_field(): void {
        $fields = [
            [ 'type' => 'email', 'value' => 'user@example.com', 'name' => 'E-mail' ],
            [ 'type' => 'phone', 'value' => '5561988887777',     'name' => 'Telefone' ],
        ];
        $contact = $this->wpf->extract_contact( $fields );
        $this->assertSame( '5561988887777', $contact['mobile_phone'] );
    }

    public function test_ignores_empty_values(): void {
        $fields = [
            [ 'type' => 'email', 'value' => '',                  'name' => 'E-mail' ],
            [ 'type' => 'text',  'value' => 'João Lima',          'name' => 'Nome' ],
        ];
        $contact = $this->wpf->extract_contact( $fields );
        $this->assertArrayNotHasKey( 'email', $contact );
    }

    public function test_picks_first_email_only(): void {
        $fields = [
            [ 'type' => 'email', 'value' => 'first@example.com',  'name' => 'E-mail' ],
            [ 'type' => 'email', 'value' => 'second@example.com', 'name' => 'Confirm' ],
        ];
        $contact = $this->wpf->extract_contact( $fields );
        $this->assertSame( 'first@example.com', $contact['email'] );
    }
}
