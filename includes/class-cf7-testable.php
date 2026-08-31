<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class RDS_CF7_Testable {

    public function extract_contact( array $data ): array {
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
