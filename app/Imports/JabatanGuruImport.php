<?php

namespace App\Imports;

use App\Models\JabatanGuru;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class JabatanGuruImport implements
    ToCollection,
    WithHeadingRow,
    WithBatchInserts,
    WithChunkReading
{
    use Importable;

    protected array $successfulRows = [];
    protected array $failures = [];

    public function __construct()
    {
        HeadingRowFormatter::default('none'); // Jangan ubah heading Excel
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $errors = [];

            $namaJabatan = trim($row['NAMA JABATAN'] ?? '');
            $deskripsi = trim($row['DESKRIPSI'] ?? '');

            // Validasi kolom wajib
            if (empty($namaJabatan)) {
                $errors[] = 'NAMA JABATAN : kosong';
            }

            // Cek duplikat nama jabatan
            if (JabatanGuru::where('nama_jabatan', $namaJabatan)->exists()) {
                $errors[] = 'NAMA JABATAN : sudah ada';
            }

            // Jika ada error, simpan ke $failures
            if (!empty($errors)) {
                $this->failures[] = [
                    'row_number' => $index + 2,
                    'row' => $row,
                    'errors' => $errors
                ];
                continue;
            }

            DB::beginTransaction();
            try {
                JabatanGuru::create([
                    'nama_jabatan' => $namaJabatan,
                    'deskripsi' => $deskripsi,
                    'status' => 'active', // default value
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id()
                ]);

                DB::commit();

                $this->successfulRows[] = [
                    'nama_jabatan' => $namaJabatan,
                    'deskripsi' => $deskripsi,
                ];
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Import Jabatan Guru error: ' . $e->getMessage());

                $this->failures[] = [
                    'row_number' => $index + 2,
                    'row' => $row,
                    'errors' => ['Database error: ' . $e->getMessage()],
                ];
            }
        }
    }

    public function failures(): array
    {
        return $this->failures;
    }

    public function successfulRows(): array
    {
        return $this->successfulRows;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function headingRow(): int
    {
        return 1;
    }
}
