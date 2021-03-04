<?php


namespace Tests\Units;


use Tests\TestCase;
use RecorderUtil\Epgdump;
use SystemUtil\Process;
use RecorderUtil\Recpt1;

class EpgdumpTest extends TestCase {
  public function test_epgdump_command () {
    $epgdump = new Epgdump( 'ssh s0 epgdump' );
    $this->assertNotEmpty( $epgdump->help() );
  }
  
  public function test_epgdump_work () {
    $ts = __DIR__.'/test-data/out-1.m2ts';
    
    $epgdump = new Epgdump( 'ssh s0 epgdump' );
    $epgdump->BS()
            ->setTsFile( '-' )
            ->setOutFile( '-' )
            ->setInput( $ts )
            ->start()
            ->wait();
    $out = $epgdump->getProcess()->getOutput();
    $this->assertNotEmpty( $out );
  }
  public function test_piped_epgdump_work(){
    // 素のパイプで epgdumpと recpt1 を使う。
    //$proc1 = new Process('ssh s0 recpt1 --b25 --strip 101 5 -');
    //[$p1_out, $p1_eror] = $proc1->start();
    //$proc2 = new Process('ssh s0 epgdump /BS - - ');
    //$proc2->setInput($proc1->getOutputStream());
    //$proc2->start();
    //$proc2->wait();
    //$out = $proc2->getOutput();
    //dd($out);
    
    // Classをストリームで接続する。
    $recpt1 = new Recpt1( 'ssh s0 recpt1' );
    $recpt1
      ->b25()
      ->strip()
      ->channel(101)
      ->duration(5)
      ->destfile('-')
      ->start();
    $p1_out = $recpt1->getProcess()->getOutputStream();
    $epgdump = new Epgdump( 'ssh s0 epgdump' );
    $epgdump->BS()
            ->setTsFile( '-' )
            ->setOutFile( '-' )
            ->setInput( $p1_out )
            ->start();
    
    $epgdump->wait();
    $out = $epgdump->getProcess()->getOutput();
    $this->assertStringContainsString('<tv ',$out);
    $this->assertStringContainsString('<programme start',$out);
    $this->assertStringContainsString('</programme>',$out);
    $this->assertStringContainsString('</tv>',$out);
  }
  public function test_piped_bs_epgdump_work(){
    // 素のパイプで epgdumpと recpt1 を使う。
    //$proc1 = new Process('ssh s0 recpt1 --b25 --strip 101 5 -');
    //[$p1_out, $p1_eror] = $proc1->start();
    //$proc2 = new Process('ssh s0 epgdump /BS - - ');
    //$proc2->setInput($proc1->getOutputStream());
    //$proc2->start();
    //$proc2->wait();
    //$out = $proc2->getOutput();
    //dd($out);
    
    // Classをストリームで接続する。
    $recpt1 = new Recpt1( 'ssh s0 recpt1' );
    $recpt1
      ->b25()
      ->strip()
      ->channel(101)
      ->duration(10)
      ->destfile('-')
      ->start();
    $p1_out = $recpt1->getProcess()->getOutputStream();
    $epgdump = new Epgdump( 'ssh s0 epgdump' );
    $epgdump
      ->BS()
      ->setTsFile( '-' )
      ->setOutFile( '-' )
      ->setInput( $p1_out )
      ->start();
    
    $epgdump->wait();
    $out = $epgdump->getProcess()->getOutput();
    
    $this->assertStringContainsString('<tv ',$out);
    $this->assertStringContainsString('<programme start',$out);
    $this->assertStringContainsString('</programme>',$out);
    $this->assertStringContainsString('</tv>',$out);
  }
  
}