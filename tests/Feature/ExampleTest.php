<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_can_change_per_page_parameter(): void
    {

        Product::factory()->count(50)->create();

        $response = $this->getJson('/api/products?per_page=10');;

        $response->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.per_page', 10);;
    }

    /** @test */
    public function it_validates_filter_parameters(): void
    {
        // Act & Assert - неверный формат in_stock
        $response = $this->getJson('/api/products?in_stock=not_boolean');

        $response->assertUnprocessable()->assertJsonIsArray('messages.in_stock');

        // Act & Assert - price_from больше price_to
        $response = $this->getJson('/api/products?price_from=100&price_to=50');

        $response->assertUnprocessable()->assertJsonIsArray('messages.price_to');

        // Act & Assert - недопустимая сортировка
        $response = $this->getJson('/api/products?sort=invalid_sort');

        $response->assertUnprocessable()->assertJsonIsArray('messages.sort');
    }

    /** @test */
    public function it_returns_correct_json_structure_for_index(): void
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'in_stock',
                        'rating',
                        'category',
                    ]
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links' => [
                        '*' => [
                            'url',
                            'label',
                            'active',
                        ]
                    ],
                    'path',
                    'per_page',
                    'to',
                    'total',
                ]
            ]);
    }

}
