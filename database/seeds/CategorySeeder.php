<?php

declare(strict_types=1);

use Slenix\Database\Seeds\Seeder;

/**
 * CategorySeeder
 *
 * Seeds the global product categories used by the whole system.
 */
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->truncate('categories');

        $names = [
            'Alimentação', 'Bebidas', 'Higiene & Limpeza', 'Consumíveis POS',
            'Mercearia', 'Padaria & Pastelaria', 'Talho & Charcutaria', 'Peixaria',
            'Frutas & Legumes', 'Laticínios & Ovos', 'Congelados', 'Enlatados & Conservas',
            'Cereais & Massas', 'Óleos, Molhos & Temperos', 'Snacks & Guloseimas',
            'Bebidas Alcoólicas', 'Sumos & Refrigerantes', 'Água', 'Café, Chá & Infusões',
            'Cuidados Pessoais', 'Perfumaria & Cosmética', 'Bebé & Infantil',
            'Limpeza da Casa', 'Descartáveis & Embalagens', 'Utilidades Domésticas',
            'Papelaria & Escritório', 'Electrodomésticos', 'Electrónica & Acessórios',
            'Telecomunicações', 'Ferramentas & Bricolage', 'Material Eléctrico',
            'Automóvel & Lubrificantes', 'Animais de Estimação', 'Farmácia & Saúde',
            'Vestuário & Calçado', 'Brinquedos & Lazer', 'Jardim & Exterior',
            'Material de Construção', 'Serviços', 'Outros',
        ];

        $now = date('Y-m-d H:i:s');

        $rows = array_map(fn (string $name): array => [
            'name'       => $name,
            'slug'       => $this->slugify($name),
            'created_at' => $now,
            'updated_at' => $now,
        ], $names);

        $this->insertBatch('categories', $rows);
    }

    /**
     * Converts a string into a URL-friendly slug.
     *
     * @param  string $text Original text (UTF-8).
     * @return string       Lowercase, ASCII, hyphen-separated slug.
     */
    private function slugify(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        $text = preg_replace('/[^a-zA-Z0-9]+/', '-', $text);

        return strtolower(trim($text, '-'));
    }
}