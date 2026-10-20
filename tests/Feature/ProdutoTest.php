<?php

namespace Tests\Feature;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProdutoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase; // Faltou essa trait na aula -> serve pra utilizar e dar refresh na memória temporária do sqlite

    public function test_cria_produto_com_sucesso(): void
    {
        $this->postJson('/api/produtos', [
            'nome' => 'Lajota',
            'preco_custo' => 100,
            'preco' => 110,
        ])->assertCreated();

        $this->assertDatabaseHas('produtos', [
            'nome' => 'Lajota']);
    }

    public function test_nao_cria_produto_com_preco_abaixo_da_margem(): void
    {
        $this->postJson('/api/produtos', [
            'nome' => 'Lajota',
            'preco_custo' => 100,
            'preco' => 109.99,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['preco']);

        $this->assertDatabaseCount('produtos', 0);
    }

    public function test_atualiza_produto_com_sucesso(): void
    {
        $produto = Produto::factory()->create([
            'nome' => 'Lajota',
            'preco_custo' => 100,
            'preco' => 110,
        ]);

        $this->patchJson('/api/produtos/'.$produto->id, [
            'nome' => 'Lajota grande',
            'preco' => 120,
        ])->assertOk()
            ->assertJsonPath('data.nome', 'Lajota grande')
            ->assertJsonPath('data.preco', 120);

        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'nome' => 'Lajota grande',
            'preco_custo' => 100,
            'preco' => 120,
        ]);
    }

    public function test_nao_atualiza_produto_com_preco_abaixo_da_margem(): void
    {
        $produto = Produto::factory()->create([
            'nome' => 'Lajota',
            'preco_custo' => 100,
            'preco' => 110,
        ]);

        $this->patchJson('/api/produtos/'.$produto->id, [
            'nome' => 'Lajota grande',
            'preco_custo' => 110,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['preco']);

        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'nome' => 'Lajota',
            'preco_custo' => 100,
            'preco' => 110,
        ]);
        $this->assertDatabaseCount('produtos', 1);
    }
}
