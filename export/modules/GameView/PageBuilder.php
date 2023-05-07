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
    const WIDTH_PIXELS = 1000;
    const HEIGHT_PIXELS = 1000;
    const WIDTH_CM = 52;
    const HEIGHT_CM = 52;
    protected array $fields = array();

    public function setPage($page) {
        $this->page = $page;
        return $this;
    }

    public function addFields(array $fields) : PageBuilder {
        $this->fields = $fields;

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