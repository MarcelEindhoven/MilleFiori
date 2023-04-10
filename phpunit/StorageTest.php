<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/BGA/Storage.php');

class StorageTest extends TestCase{
    protected Storage $sut;

    protected function setUp(): void {
        $this->sut = new Storage();
    }
}
?>
