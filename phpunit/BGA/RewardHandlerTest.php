<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/RewardHandler.php');
include_once(__DIR__.'/../../export/modules/BGA/EventEmitter.php');

class RewardHandlerTest extends TestCase{
    const DEFAULT_PLAYER_ID = 3;
    const DEFAULT_POSITION = 5;
    const DEFAULT_PROPERTY_NAME = 'name';
    const DEFAULT_POSITION_DATA = [RewardHandlerTest::DEFAULT_PLAYER_ID => [RewardHandlerTest::DEFAULT_PROPERTY_NAME => RewardHandlerTest::DEFAULT_POSITION]];
    

    public function setup() : void {
        $this->player_id = RewardHandlerTest::DEFAULT_PLAYER_ID;

        $this->sut = RewardHandler::createFromPlayerProperties(RewardHandlerTest::DEFAULT_POSITION_DATA);
    }

    public function testGet_KnownPlayer_Position5() {
        // Arrange
        // Act
        // $position = $this->sut[$this->player_id];
        // Assert
        
    }
}
?>
