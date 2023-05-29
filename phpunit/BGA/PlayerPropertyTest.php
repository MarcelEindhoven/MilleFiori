<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/PlayerProperty.php');
include_once(__DIR__.'/../../export/modules/BGA/EventEmitter.php');

class PlayerPropertyTest extends TestCase{
    const DEFAULT_PLAYER_ID = 3;
    const DEFAULT_POSITION = 5;
    const DEFAULT_PROPERTY_NAME = 'name';
    const DEFAULT_POSITION_DATA = [PlayerPropertyTest::DEFAULT_PLAYER_ID => [PlayerPropertyTest::DEFAULT_PROPERTY_NAME => PlayerPropertyTest::DEFAULT_POSITION]];
    

    public function setup() : void {
        $this->player_id = PlayerPropertyTest::DEFAULT_PLAYER_ID;

        $this->sut = PlayerProperty::CreateFromPlayerProperties(PlayerPropertyTest::DEFAULT_PROPERTY_NAME, PlayerPropertyTest::DEFAULT_POSITION_DATA);
    }

    public function testGet_UnknownPlayer_Exception() {
        // Arrange
        $this->expectWarning();
        // Act
        $dummy = $this->sut[1];
        // Assert
    }

    public function testGet_KnownPlayer_Position5() {
        // Arrange
        // Act
        $position = $this->sut[$this->player_id];
        // Assert
        $this->assertEquals(PlayerPropertyTest::DEFAULT_POSITION, $position);
    }

    public function testSet_KnownPlayer_ReturnSetValue() {
        // Arrange
        $value = 7;
        // Act
        $this->sut[$this->player_id] = $value;
        // Assert
        $this->assertEquals($value, $this->sut[$this->player_id]);
    }
}
?>
