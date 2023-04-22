<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentOcean.php');
include_once(__DIR__.'/../../export/modules/BGA/Storage.php');

class CurrentOceanTest extends TestCase{
    const DEFAULT_SELECTABLE_FIELD_IDS = [];
    const DEFAULT_POSITION_DATA = [3 => [Ocean::KEY_PLAYER_POSITION => 5]];

    protected CurrentOcean $sut;

    protected function setUp(): void {
        $this->position_data = CurrentOceanTest::DEFAULT_POSITION_DATA;
        $this->sut = CurrentOcean::create($this->position_data);
    }

    public function testProperties_GetPlayer_GetBucket() {
        // Arrange
        // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        // Act
        $data = $this->sut->getSelectableFields();
        // Assert
        $this->assertEquals(CurrentOceanTest::DEFAULT_SELECTABLE_FIELD_IDS, $data);
    }
}
?>
