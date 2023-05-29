<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/EventEmitter.php');
include_once(__DIR__.'/../../export/modules/BGA/RewardHandler.php');
include_once(__DIR__.'/../../export/modules/BGA/UpdatePlayerRobotProperties.php');

class RewardHandlerTest extends TestCase{
    const DEFAULT_PLAYER_ID = 3;
    const DEFAULT_SCORE = 4;
    const DEFAULT_PROPERTY_NAME = 'name';    

    public function setup() : void {
        $this->player_id = RewardHandlerTest::DEFAULT_PLAYER_ID;

        $this->mock_array = $this->createMock(\ArrayAccess::class);
        $this->mock_properties_array = $this->createMock(\ArrayAccess::class);
        $this->sut = RewardHandler::createFromPlayerProperties($this->mock_array);

        $this->mock_emitter = $this->createMock(EventEmitter::class);
        $this->sut->setEventEmitter($this->mock_emitter);
    }

    public function testPoints_5_Score9() {
        // Arrange
        $this->points = 5;
        $this->mock_array->expects($this->exactly(1))->method('offsetGet')->with($this->player_id)->will($this->returnValue($this->mock_properties_array));
        $this->mock_properties_array->expects($this->exactly(1))->method('offsetGet')->with(UpdatePlayerRobotProperties::KEY_SCORE)->will($this->returnValue(RewardHandlerTest::DEFAULT_SCORE));
        $this->mock_properties_array->expects($this->exactly(1))->method('offsetSet')->with(UpdatePlayerRobotProperties::KEY_SCORE, RewardHandlerTest::DEFAULT_SCORE + $this->points);
        // Act
        $this->sut->gainedPoints($this->player_id, $this->points);
        // Assert
    }

    public function testAdditionalReward_Gained_Emit() {
        // Arrange
        $this->additional_reward = 'select_extra_card';
        // Event content to be decided
        $this->mock_emitter->expects($this->exactly(1))->method('emit')->with($this->additional_reward, []);
        // Act
        $this->sut->gainedAdditionalReward($this->player_id, $this->additional_reward);
        // Assert
    }
}
?>
