<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentCategories.php');
include_once(__DIR__.'/../../export/modules/BGA/Storage.php');

class CurrentCategoriesTest extends TestCase{
    const SELECTABLE_FIELDS = [];

    protected CurrentCategories $sut;

    protected function setUp(): void {
        $this->mock_storage = $this->createMock(\NieuwenhovenGames\BGA\Storage::class);
        $this->sut = CurrentCategories::create($this->mock_storage);
    }

    public function testProperties_GetPlayer_GetBucket() {
        // Arrange
        // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        // Act
        $data = $this->sut->getSelectableFields();
        // Assert
        $this->assertEquals(CurrentCategoriesTest::SELECTABLE_FIELDS, $data);
    }
}
?>
