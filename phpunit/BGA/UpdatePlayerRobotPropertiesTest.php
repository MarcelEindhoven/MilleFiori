<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/UpdatePlayerRobotProperties.php');
include_once(__DIR__.'/../../export/modules/BGA/EventEmitter.php');

class UpdatePlayerRobotPropertiesTest extends TestCase{
    const DEFAULT_PLAYER_ID = 3;
    const DEFAULT_POSITION = 5;
    const DEFAULT_KEY = 'key';
    const DEFAULT_DATA = [UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID => [UpdatePlayerRobotPropertiesTest::DEFAULT_KEY => UpdatePlayerRobotPropertiesTest::DEFAULT_POSITION]];

    protected UpdatePlayerRobotProperties $sut;

    protected function setUp(): void {
        $this->sut = new UpdatePlayerRobotProperties(UpdatePlayerRobotPropertiesTest::DEFAULT_DATA);
    }

    public function testGet_InitialValue_DefaultReturned() {
        // Arrange
        // Act
        $position = $this->sut[UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID][UpdatePlayerRobotPropertiesTest::DEFAULT_KEY];
        // Assert
        $this->assertEquals(UpdatePlayerRobotPropertiesTest::DEFAULT_POSITION, $position);
    }
}
?>
