<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class PageBuilder {
    const PAGE_NAME = 'millefiori_millefiori';
    const FIELD_BLOCK = 'field';
    const KEY_CATEGORY = 'CATEGORY';
    const KEY_FIELD_ID = 'ID';
    const WIDTH_PIXELS = 868;
    const HEIGHT_PIXELS = 872;
    const WIDTH_CM = 52;
    const HEIGHT_CM = 52;
    protected array $fields = array();

    public function setPage($page) {
        $this->page = $page;
        return $this;
    }

    static public function completeIDs(string $category, array $ids) : array {
        $completeIDs = [];
        foreach ($ids as $id) {
            $completeIDs[] = PageBuilder::completeID($category, $id);
        }
        return $completeIDs;
    }

    static public function completeID(string $category, string $id) : string {
        return PageBuilder::FIELD_BLOCK . '_' . $category . '_' . $id;
    }

    public function addFields(string $category, array $fields) : PageBuilder {
        foreach($fields as $field) {
            $field[PageBuilder::KEY_CATEGORY] = $category;
            //$field[PageBuilder::KEY_FIELD_ID] = PageBuilder::completeID($category, $field[PageBuilder::KEY_FIELD_ID]);
            $this->fields[] = $field;
        }
        return $this;
    }

    public function generateContent() : PageBuilder {
        $this->page->begin_block(PageBuilder::PAGE_NAME, PageBuilder::FIELD_BLOCK);
        foreach($this->fields as $field) {
            $field['LEFT'] = (int)($field['LEFT'] * PageBuilder::WIDTH_PIXELS / PageBuilder::WIDTH_CM);
            $field['TOP'] = (int)($field['TOP'] * PageBuilder::HEIGHT_PIXELS / PageBuilder::HEIGHT_CM);
            $this->page->insert_block(PageBuilder::FIELD_BLOCK, $field);
        }
        return $this;
    }
}