<?php


namespace RecorderUtil;


use RecorderUtil\Epgdump\EpgdumpCommand;
use SystemUtil\Process;

class Epgdump {
  
  public $cmd = 'epgdump';
  public $opt;
  protected $outfile;
  protected $ts_file;
  protected $proc;
  protected $stdin;
  public function __construct($cmd=null){
    $this->cmd = $cmd ?? $this->cmd;
  }
  public function help(){
    $args = array_merge( [], preg_split('/\s/',$this->cmd), ['-h'] );
    $args = array_filter($args,'trim');
    $proc = new Process($this->cmd);
    $proc->run();
    $out = $proc->getOutput();
    return $out;
  }
  protected function genArgs() {
    $args = [];
    if ( empty($this->opt ) ){
      throw new \RuntimeException($this->help());
    }
    if ( !( $this->ts_file == '-' && !empty($this->stdin) ) ) {
      throw new \RuntimeException('ts_file or stdin should be specified');
    }
    
    $args = [$this->opt, $this->ts_file, $this->outfile];
    $args = array_merge( [], preg_split('/\s/',$this->cmd), $args );
    $args = array_filter($args,'trim');
    return $args;
  }
  public function getProcess() : Process{
    return $this->proc;
  }
  public function BS(){
    $this->opt = '/BS';
    return $this;
  }
  public function CS(){
    $this->opt = '/CS';
    return $this;
  }
  public function setOutFile($filename){
    if ( !( $filename == '-' || is_writable(dirname($filename) )) ){
      throw new \InvalidArgumentException(' invaild output.');
    }
    $this->outfile= $filename;
    return $this;
  }
  public function setTsFile($filename){
    if ( !( $filename == '-' || is_readable($filename))  ){
      throw new \InvalidArgumentException(' invaild input.');
    }
    $this->ts_file = $filename;
    return $this;
  }
  public function setInput($input){
    $this->stdin = $input;
    return $this;
  }
  public function ontvcode($code){
    $this->opt = $code;
    return $this;
  }
  public function start(){
    $args = $this->genArgs();
    //dd($args);
    $this->proc = new Process( $args );
    if ( $this->ts_file == '-' && !empty($this->stdin)){
      $this->proc->setInput($this->stdin);
    }
    $this->proc->start();
    return $this;
  }
  public function wait(){
    $this->proc && $this->proc->wait();
    return $this;
  }
  public function run(){
    $this->start();
    $this->wait();
    return $this;
  }
}