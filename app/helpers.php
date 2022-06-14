<?php 

declare( strict_types = 1 );

/**
 * Helper function to format the amount
 */
function formatDollarAmount( float $amount ): string 
{
    $is_negative = $amount < 0;

    return ($is_negative ? '-' : '') . '$' . number_format( abs( $amount ), 2 );
}

/**
 * Helper function to format date
 */
function formatDate( string $date ): string 
{
    return date( 'M j, Y', strtotime( $date ) );
}