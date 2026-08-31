<?php

define( 'ABSPATH', __DIR__ . '/' );

// WordPress function stubs
function sanitize_email( string $v ): string     { return filter_var( $v, FILTER_SANITIZE_EMAIL ) ?: ''; }
function sanitize_text_field( string $v ): string { return trim( strip_tags( $v ) ); }
function sanitize_title( string $v ): string      { return strtolower( preg_replace( '/[^a-z0-9]+/i', '-', $v ) ); }
function wp_json_encode( mixed $v ): string|false { return json_encode( $v ); }
function wp_strip_all_tags( string $v ): string   { return strip_tags( $v ); }
function is_wp_error( mixed $v ): bool            { return false; }
function wp_remote_retrieve_response_code( array $r ): int { return $r['status'] ?? 0; }
function wp_remote_retrieve_body( array $r ): string       { return $r['body'] ?? ''; }

require_once __DIR__ . '/../includes/class-rd-api.php';
require_once __DIR__ . '/../includes/class-cf7-testable.php';
require_once __DIR__ . '/../includes/class-wpforms-testable.php';
