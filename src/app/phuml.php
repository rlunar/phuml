<?php

require_once( 'config/config.php' );

function showUsage() 
{
    echo <<<USAGE
Usage: {$argv[0]} [OPTIONS] <FILE|DIRECTORY> ... <OUTFILE>

Options: 
    -g      The structure generator to use (default: tokenizer)
    -w      The structure writer to use (default: dot)    
    -r      Scan given directories recursively

USAGE;
}

$options = getopt( 'g:w:r' );
$optionCount =   ( isset( $options['g'] ) ? 2 : 0 ) 
               + ( isset( $options['w'] ) ? 2 : 0 ) 
               + ( isset( $options['r'] ) ? 1 : 0 );

$argv = array_slice( $argv, $optionCount  + 1 );
$argc = count( $argv );

if ( $argc < 2 ) 
{
    showUsage();
    exit( 1 );
}

$phuml = new plPhuml();

if ( isset( $options['g'] ) === true ) 
{
    $phuml->generator = plStructureGenerator::factory( $options['g'] );
}

if ( isset( $options['w'] ) === true ) 
{
    $phuml->writer = plStructureWriter::factory( $options['w'] );
}

$recursive = ( isset( $options['r'] ) === true ); 

for ( $i = 0; $i < $argc - 1; $i++ ) 
{
    if ( is_dir( $argv[$i] ) === true )  
    {
        $phuml->addDirectory( $argv[$i], 'php', $recursive );         
    } 
    else if ( is_file( $argv[$i] ) === true ) 
    {
        $phuml->addFile( $argv[$i] );
    }
}

echo 'Generating structure file "', $argv[$argc - 1], '" ...', "\n";

$phuml->generate( $argv[$argc - 1] );

echo 'Structure sucessfully generated.', "\n";

?>
