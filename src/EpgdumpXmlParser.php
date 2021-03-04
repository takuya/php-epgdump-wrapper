<?php


namespace RecorderUtil;


class EpgdumpXmlParser {
  
  protected $input;
  protected $dom;
  
  
  public function __construct ( $f_in = null ) {
    $f_in && $this->open( $f_in );
  }
  
  public function programme($datetime_format='c'){
    $this->load_xml();
    /** @var \SimpleXMLElement[] $list */
    $list =  $this->dom->programme;
    foreach ( $list as $e) {
      $e['start'] = date($datetime_format, strtotime((String)$e['start']));
      $e['stop'] = date($datetime_format, strtotime((String)$e['stop']));
    }
    return $list;
  }
  
  protected function load_xml () {
    return $this->dom ?: $this->dom = simplexml_load_string( stream_get_contents($this->input) );
  }
  
  public
  function open ( $file ) {
    if ( is_resource( $file ) ) {
      $this->openStream( $file );
    }
    if ( is_string( $file ) ) {
      $this->openFile( $file );
    }
    throw new \InvalidArgumentException();
  }
  
  
  protected
  function openFile ( $f_in ) {
    if ( !is_readable( $f_in ) ) {
      throw new \RuntimeException( 'cannot open file.' );
    }
    $this->input = fopen( $f_in, 'r' );
  }
  
  protected
  function openStream ( $f_in ) {
    if ( get_resource_type( $f_in ) != 'stream' ) {
      throw new \RuntimeException( 'cannot open file.' );
    }
    $this->input = $f_in;
  }
}