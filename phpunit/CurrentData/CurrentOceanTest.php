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
    const SELECTABLE_FIELDS = [];

    protected CurrentOcean $sut;

    protected function setUp(): void {
        $this->mock_storage = $this->createMock(\NieuwenhovenGames\BGA\Storage::class);
        $this->sut = CurrentOcean::create($this->mock_storage);
    }

    public function testProperties_GetPlayer_GetBucket() {
        // Arrange
        // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        // Act
        $data = $this->sut->getSelectableFields();
        // Assert
        $this->assertEquals(CurrentOceanTest::SELECTABLE_FIELDS, $data);
    }
}
?>
