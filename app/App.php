<?php

declare(strict_types = 1);

/**
 *  Step 1: This function will get the filename and save it in an array
 */
function getTransactionFiles( string $dir_path ): array 
{
    // creating an empty array to be filled later
    $files = [];

    // scandir — List files and directories inside the specified path ( to an array )
    // foreach will evaluate the items in the array, read from scandir
    foreach( scandir( $dir_path ) as  $file ) {
        // is_dir — Tells whether the filename is a directory
        // checks if value being process is a path and skips it
        if( is_dir( $file ) )  continue;
        // if value is a file name, it saves it in the array
        // appending the argument $dir_path will give the complete path of the file + name
        $files[] =  $dir_path . $file;
        // return the array
        return $files;
    } 
}

/**
 * Step 2: This function will read the file and exract the data by reading it line-by-line
 */
function getTransactions( string $file_name, ?callable $transactionHandler = null ): array
{
    // file_exists — Checks whether a file or directory exists
    // check if the file_name exists, if it doesn't it will throw a custom error
    if( ! file_exists( $file_name ) ) {
        // trigger_error — Generates a user-level error/warning/notice message
        // E_USER_ERROR (int)	User-generated error message. 
        // This is like an E_ERROR, except it is generated in PHP code by using the PHP function trigger_error().	
        trigger_error( "Alang pakanitang file.", E_USER_ERROR );
    }

    // if the file exists, open the file
    // fopen — Opens file or URL
    //  mode 'r'	Open for reading only; place the file pointer at the beginning of the file.
    $file = fopen( $file_name, 'r' );
    
     $transactions = [];

    // fgetcsv — Gets line from file pointer and parse for CSV fields

    // gets the first line of data that is the name of the columns
    // moves the pointer to the next line of data
    fgetcsv( $file );
    
     // read the file line by line and put the data in an array
    while( ( $transaction = fgetcsv( $file ) ) !== false )  {
        if( $transactionHandler !== null ) {
            // if a callable function exists, it will process the data before adding to array
            $transaction = $transactionHandler( $transaction );
        }
        
        $transactions[] = $transaction;
    }

    return $transactions;
}

/**
 * Step 3: Parsing and Formatting the data from the file
 *              Use this function in step 2, while looping on transactions
 *               In views file, use the return keys 
 */
function extractTransaction( array $transaction_row): array
{
    // array destructuring
    [ $date, $check_number, $description, $amount ] = $transaction_row;

    // replace unwanted characters and convert amount to numerical equivalent
    $amount = ( float ) str_replace( [ '$', ',' ], '', $amount );

    // return value(s) in an array
    return [
        'date'                              =>      $date,
        'check_number'           =>      $check_number,
        'description'                  =>      $description,
        'amount'                         =>      $amount
    ];
}

/**
 * Step 4:  Function to calculate totals
 */
function calculateTotals( array $transactions ): array
{
    // settting inital values and putting them in an array
    $totals = [ 
            'net_total'         =>      0, 
            'total_income'  =>      0, 
            'total_expense' =>      0 
        ];

    // looping $transactions array to calculate totals
    foreach( $transactions as $transaction ) {
        $totals[ 'net_total' ] += $transaction[ 'amount' ];

        if( $transaction[ 'amount' ] >= 0 ) {
            $totals[ 'total_income' ] += $transaction[ 'amount' ];
        } else {
            $totals[ 'total_expense'] += $transaction [ 'amount' ];
        }
    }

    // returning the $totals array
    return $totals;
}