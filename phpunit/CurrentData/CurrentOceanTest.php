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
    const DEFAULT_SELECTABLE_FIELD_IDS = ['field_ocean_8'];
    const DEFAULT_POSITION_DATA = [3 => [Ocean::KEY_PLAYER_POSITION => 5]];

    protected CurrentOcean $sut;

    protected function setUp(): void {
        $this->position_data = CurrentOceanTest::DEFAULT_POSITION_DATA;
        $this->sut = CurrentOcean::create($this->position_data);
    }

    public function testProperties_GetPlayer_GetBucket() {
        // Arrange
        $player_id = 3;
        $card_id = 7;
        // Act
        $data = $this->sut->getSelectableFieldIDs($player_id, $card_id);
        // Assert
        $this->assertEquals(CurrentOceanTest::DEFAULT_SELECTABLE_FIELD_IDS, $data);
    }

    public function testTooltips_Get_Array() {
        // Arrange
        // Act
        $tooltips = $this->sut->getTooltipsCards();
        // Assert
        $this->assertCount(count(Ocean::PLACES_PER_CARD), $tooltips);
    }
}
?>
