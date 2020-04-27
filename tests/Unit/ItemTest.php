<?php

namespace Tests\Unit;

use App\Item;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    private $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->item = new Item([
            'name' => 'Nom de mon item',
            'content' => 'Ceci est un petit content de test pour mes TU !!'
        ]);
    }

    public function testIsValidNominal()
    {
        $this->assertTrue($this->item->isValid());
    }

    public function testIsNotValidNameEmpty()
    {
        $this->item->name = '';
        $this->assertFalse($this->item->isValid());
    }

    public function testIsNotValidNameNull()
    {
        $this->item->name = null;
        $this->assertFalse($this->item->isValid());
    }

    public function testIsNotValidContentToLong()
    {
        $this->item->content = Str::random(1500);
        $this->assertFalse($this->item->isValid());
    }
}
