<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameView/PageBuilder.php');

include_once(__DIR__.'/../../export/modules/BGA/PageInterface.php');

class PageBuilderTest extends TestCase{
    protected PageBuilder $builder;

    protected function setUp(): void {
        $this->mock = $this->createMock(\NieuwenhovenGames\BGA\PageInterface::class);
        $this->builder = new PageBuilder();
        $this->builder->setPage($this->mock);
    }

    private function arrangeExpectHeader() {
        $this->mock->expects($this->exactly(1))->method('begin_block')->withConsecutive(
             [$this->equalTo(PageBuilder::PAGE_NAME), $this->equalTo(PageBuilder::FIELD_BLOCK)],
            );
    }

    public function testEmptyHeaderOnly() {
        // Arrange
        $this->arrangeExpectHeader();
        // Act
        $this->builder->generateContent();
        // Assert
    }

    public function testSingleFieldHeaderPlusInsert() {
        // Arrange
        $left_cm = 15;
        $top_cm = 5;
        $width_pixels = 954;
        $this->arrangeExpectHeader();
        $field_input = array (
            'CATEGORY' => 'Harbour',
            'ID' => 10,
            'LEFT' => 15.1,
            'TOP' => 5);
        $field_expected = array (
            'CATEGORY' => 'Harbour',
            'ID' => '10',
            'LEFT' => (int)($field_input['LEFT'] * PageBuilder::WIDTH_PIXELS / PageBuilder::WIDTH_CM),
            'TOP' => (int)($field_input['TOP'] * PageBuilder::HEIGHT_PIXELS / PageBuilder::HEIGHT_CM));
        $this->mock->expects($this->exactly(1))->method('insert_block')->withConsecutive(
            [$this->equalTo(PageBuilder::FIELD_BLOCK), $this->equalTo($field_expected)]
        );
        // Act
        $this->builder->addFields([$field_input])->generateContent();
        // Assert
    }
}
?>
