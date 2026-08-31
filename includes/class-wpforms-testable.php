<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class RDS_WPForms_Testable {

    public function extract_contact( array $fields ): array {
        $contact = [];

        foreach ( $fields as $field ) {
            $type  = $field['type'] ?? '';
            $value = sanitize_text_field( $field['value'] ?? '' );

            if ( ! $value ) continue;

            if ( $type === 'email' && empty( $contact['email'] ) ) {
                $contact['email'] = sanitize_email( $value );
            } elseif ( in_array( $type, [ 'name', 'text' ], true ) && empty( $contact['name'] ) ) {
                $label = strtolower( $field['name'] ?? '' );
                if ( str_contains( $label, 'nome' ) || str_contains( $label, 'name' ) ) {
                    $contact['name'] = $value;
                }
            } elseif ( $type === 'phone' && empty( $contact['mobile_phone'] ) ) {
                $contact['mobile_phone'] = $value;
            }
        }

        return $contact;
    }
}
