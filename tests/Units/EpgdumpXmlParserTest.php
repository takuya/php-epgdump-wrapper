<?php


namespace Tests\Units;


use Tests\TestCase;
use RecorderUtil\Epgdump;
use SystemUtil\Process;
use RecorderUtil\Recpt1;
use RecorderUtil\EpgdumpXmlParser;

class EpgdumpXmlParserTest extends TestCase {
  
  public function test_epgdump_xml_parse () {
    $xml = __DIR__.'/test-data/epgdump-sample.xml';
    $parser = new EpgdumpXmlParser($xml);
    $parser->open($xml);
    $parser->open(fopen($xml,'r'));
    $list = $parser->programme();
    $this->assertEquals(61 ,sizeof($list));
  }
  public function test_epgdump_stream_parse(){
    $ts = __DIR__.'/test-data/out-1.m2ts';
  
    $epgdump = new Epgdump( 'ssh s0 epgdump' );
    $epgdump->BS()
            ->setTsFile( '-' )
            ->setOutFile( '-' )
            ->setInput( $ts )
            ->start()
            ->wait();
    $out = $epgdump->getProcess()->getOutputStream();
    $parser = new EpgdumpXmlParser($out);
    $list = $parser->programme();
    $this->assertEquals(61 ,sizeof($list));
  }
}