<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameSetup/FieldsSetup.php');
include_once(__DIR__.'/../../export/modules/BGA/Storage.php');

class FieldsSetupTest extends TestCase{
    protected FieldsSetup $sut;

    protected function setUp(): void {
        $this->mock_database = $this->createMock(\NieuwenhovenGames\BGA\Storage::class);
        $this->sut = FieldsSetup::create($this->mock_database);
    }

    public function testFields_Single_CreateBucket() {
        // Arrange
        $bucket_name = 'field';
        $field_id = 'field_ocean_1';
        $this->mock_database->expects($this->exactly(1))
        ->method('createBucket')
        ->with($this->equalTo($bucket_name), $this->equalTo([FieldsSetup::FIELD_ID_NAME, FieldsSetup::PLAYER_ID_NAME]), [[$field_id, FieldsSetup::NOT_OCCUPIED]]);

        // Act
        $this->sut->setup([$field_id]);
        // Assert
    }
}
?>
