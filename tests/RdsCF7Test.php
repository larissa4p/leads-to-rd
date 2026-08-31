<?php

use PHPUnit\Framework\TestCase;

class RdsCF7Test extends TestCase {

    private RDS_CF7_Testable $cf7;

    protected function setUp(): void {
        $this->cf7 = new RDS_CF7_Testable();
    }

    public function test_extracts_email_from_standard_key(): void {
        $contact = $this->cf7->extract_contact([ 'email' => 'user@example.com' ]);
        $this->assertSame( 'user@example.com', $contact['email'] );
    }

    public function test_extracts_email_from_your_email_key(): void {
        $contact = $this->cf7->extract_contact([ 'your-email' => 'user@example.com' ]);
        $this->assertSame( 'user@example.com', $contact['email'] );
    }

    public function test_extracts_name_from_nome_key(): void {
        $contact = $this->cf7->extract_contact([
            'email' => 'user@example.com',
            'nome'  => 'Maria Silva',
        ]);
        $this->assertSame( 'Maria Silva', $contact['name'] );
    }

    public function test_extracts_phone_from_telefone_key(): void {
        $contact = $this->cf7->extract_contact([
            'email'    => 'user@example.com',
            'telefone' => '5561999999999',
        ]);
        $this->assertSame( '5561999999999', $contact['mobile_phone'] );
    }

    public function test_returns_empty_when_no_email(): void {
        $contact = $this->cf7->extract_contact([ 'nome' => 'Maria' ]);
        $this->assertArrayNotHasKey( 'email', $contact );
    }

    public function test_strips_html_from_name(): void {
        $contact = $this->cf7->extract_contact([
            'email' => 'user@example.com',
            'name'  => '<b>Maria</b>',
        ]);
        $this->assertSame( 'Maria', $contact['name'] );
    }
}
