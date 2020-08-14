<?php

namespace Tests\Feature;

use App\Image;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreImageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        factory(User::class)->create();
        Storage::fake('local');
    }
    
    /** @test */
    public function itSavesUploadedImage()
    {
        $file = UploadedFile::fake()->image('sample.jpg');
        $response = $this->postJson('/api/image', [
            'image' => $file
        ]);

        $response->assertCreated();
        $response->assertJsonStructure(['image_id']);
        $this->assertEquals(Image::count(), 1);
        Storage::disk('local')->assertExists('public/' . $file->hashName());
    }

    /** @test */
    public function itAllowsOnlyJPEGAndPNGFile()
    {
        $jpeg = UploadedFile::fake()->image('sample.jpeg');
        $png = UploadedFile::fake()->image('sample.png');
        $gif = UploadedFile::fake()->image('sample.gif');

        $this->postJson('/api/image', [
            'image' => $jpeg
        ])->assertCreated();

        $this->postJson('/api/image', [
            'image' => $png
        ])->assertCreated();

        $this->postJson('/api/image', [
            'image' => $gif
        ])->assertJsonValidationErrors(['image']);
    }
}
