<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class RDS_CF7 {

    public function __construct() {
        add_action( 'wpcf7_mail_sent', [ $this, 'on_submit' ] );
    }

    public function on_submit( WPCF7_ContactForm $form ): void {
        if ( ! RDS_Settings::get( 'cf7', 1 ) ) return;

        $token = RDS_Settings::get( 'token' );
        if ( ! $token ) return;

        $submission = WPCF7_Submission::get_instance();
        if ( ! $submission ) return;

        $data    = $submission->get_posted_data();
        $contact = $this->extract_contact( $data );

        if ( empty( $contact['email'] ) ) return;

        $identifier = $form->name() ?: RDS_Settings::get( 'identifier', 'wordpress-form' );

        $api    = new RDS_API( $token );
        $result = $api->convert( $identifier, $contact );

        RDS_Log::insert( $identifier, $contact['email'], $result );
    }

    private function extract_contact( array $data ): array {
        $contact = [];

        $email_keys = [ 'email', 'your-email', 'e-mail', 'email-address' ];
        foreach ( $email_keys as $key ) {
            if ( ! empty( $data[ $key ] ) ) {
                $contact['email'] = sanitize_email( $data[ $key ] );
                break;
            }
        }

        $name_keys = [ 'name', 'your-name', 'nome', 'full-name', 'fullname' ];
        foreach ( $name_keys as $key ) {
            if ( ! empty( $data[ $key ] ) ) {
                $contact['name'] = sanitize_text_field( $data[ $key ] );
                break;
            }
        }

        $phone_keys = [ 'phone', 'tel', 'telefone', 'your-phone', 'celular', 'mobile' ];
        foreach ( $phone_keys as $key ) {
            if ( ! empty( $data[ $key ] ) ) {
                $contact['mobile_phone'] = sanitize_text_field( $data[ $key ] );
                break;
            }
        }

        return $contact;
    }
}
