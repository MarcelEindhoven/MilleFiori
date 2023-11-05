<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameSetup/GameSetup.php');
include_once(__DIR__.'/../../export/modules/BGA/Storage.php');
include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Database.php');

class GameSetupTest extends TestCase{
    protected GameSetup $sut;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(\NieuwenhovenGames\BGA\Database::class);
        $this->sut = GameSetup::create($this->mock_database);
    }

    public function testFields_Integration_CreateBucket() {
        // Arrange
        $this->mock_database->expects($this->exactly(1))
        ->method('query');

        // Act
        $this->sut->setupBoard();
        // Assert
    }
}
?>
