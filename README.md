# epgdump command php wrapper

epgdump のコマンドをいつも間違えるので、ラッパーを書いた

## sample 01
```php
<?php

    $ts = 'sample.ts';
    
    $epgdump = new Epgdump();
    $xml = $epgdump->BS()
            ->setTsFile( '-' )
            ->setOutFile( '-' )
            ->setInput( $ts )
            ->start()
            ->wait()
            ->getProcess()
            ->getOutput();
    
```

## sample 02
ssh経由で
```php
<?php

    $ts = 'sample.ts';
    
    $epgdump = new Epgdump('ssh 192.168.10.5 epgdump');
    $xml = $epgdump->BS()
            ->setTsFile( '-' )
            ->setOutFile( '-' )
            ->setInput( $ts )
            ->start()
            ->wait()
            ->getProcess()
            ->getOutput();
    
```
## sample 03
recpt1 の出力をそのまま使う。
```php
<?php
    $proc1 = new Process('ssh s0 recpt1 --b25 --strip 101 5 -');
    [$p1_out, $p1_eror] = $proc1->start();
    
    $epgdump = new Epgdump('ssh 192.168.10.5 epgdump');
    $xml = $epgdump->BS()
        ->setTsFile( '-' )
        ->setOutFile( '-' )
        ->setInput( $p1_out )
        ->start()
        ->wait()
        ->getProcess()
        ->getOutput();
    
```
Process クラスは ` composer require takuya/process` でインストール。


