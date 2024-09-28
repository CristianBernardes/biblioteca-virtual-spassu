<?php

namespace Tests\Unit;

use Tests\TestCase;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RelatorioAutoresLivrosAssuntosExport;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RelatorioAutoresLivrosAssuntosExportTest extends TestCase
{
    /** @test */
    public function exportacao_relatorio_autores_livros_assuntos()
    {
        // Gere o arquivo Excel usando a exportação
        $response = Excel::download(new RelatorioAutoresLivrosAssuntosExport, 'relatorio_autores_livros_assuntos.xlsx');

        // Salve o arquivo temporariamente
        $filePath = tempnam(sys_get_temp_dir(), 'relatorio_') . '.xlsx';
        file_put_contents($filePath, $response->getFile()->getContent());

        // Carregue o arquivo Excel gerado
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        // Verifique os cabeçalhos na primeira linha
        $this->assertEquals('Título do Livro', $worksheet->getCell('A1')->getValue());
        $this->assertEquals('Editora do Livro', $worksheet->getCell('B1')->getValue());
        $this->assertEquals('Ano de Publicação', $worksheet->getCell('C1')->getValue());
        $this->assertEquals('Autores', $worksheet->getCell('D1')->getValue());
        $this->assertEquals('Assuntos', $worksheet->getCell('E1')->getValue());

        // Limpa o arquivo temporário
        unlink($filePath);
    }
}
