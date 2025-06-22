<?php

namespace App\Console\Commands;

use App\Models\KelasSiswa;
use App\Models\RuangKelas;
use App\Models\TahunPelajaran;
use App\Models\Tingkat;
use Illuminate\Console\Command;

/**
 * Class MoveUpClass
 *
 * This console command processes automatic student promotion and graduation
 * based on the current academic year and grade level.
 *
 * @package App\Console\Commands
 */
class MoveUpClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:move-up-class';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically processes student promotion and graduation.';

    /**
     * Execute the console command.
     *
     * This method will:
     * - Retrieve all active students.
     * - Check whether the student is in their final grade based on the level (SD, SMP, SMA, etc.).
     * - If yes, mark them as graduated.
     * - If not, promote them to the next grade and assign them to the corresponding class.
     *
     * @return void
     */
    public function handle(): void
    {
        $tahunAjaranAktif = TahunPelajaran::where('status', 'active')->first();

        if (!$tahunAjaranAktif) {
            $this->error('No active academic year found.');
            return;
        }

        $kelasSiswaAktif = KelasSiswa::with(['siswa', 'ruangKelas.tingkat'])
            ->where('status', 'active')
            ->get();

        $totalNaik = 0;
        $totalLulus = 0;

        foreach ($kelasSiswaAktif as $ks) {
            $siswa = $ks->siswa;
            $tingkat = $ks->ruangKelas->tingkat;
            $jenjang = $tingkat->jenjang;
            $currentTingkat = (int)$tingkat->nama_tingkat;

            // Determine the final grade per level
            $batasTingkat = match ($jenjang) {
                'SD', 'MI' => 6,
                'SMP', 'MTS' => 9,
                'SMA', 'MA', 'SMK' => 12,
                default => 12,
            };

            // Graduation condition
            if ($currentTingkat >= $batasTingkat) {
                $ks->update(['status' => 'graduated', 'tanggal_lulus' => now()]);
                $siswa->update(['status' => 'graduated', 'tanggal_lulus' => now()]);
                $totalLulus++;
                continue;
            }

            // Find next grade
            $nextTingkat = Tingkat::where('jenjang', $jenjang)
                ->where('nama_tingkat', (string)($currentTingkat + 1))
                ->first();

            if (!$nextTingkat) {
                $this->warn("Next grade not found for student ID {$siswa->id_siswa}");
                continue;
            }

            // Find or create next class
            $ruangBaru = RuangKelas::firstOrCreate([
                'tahun_pelajaran_id' => $tahunAjaranAktif->id_tahun_pelajaran,
                'tingkat_id' => $nextTingkat->id_tingkat,
                'jurusan_id' => $ks->ruangKelas->jurusan_id,
                'nama_ruang_kelas' => $nextTingkat->nama_tingkat . 'A',
            ]);

            // Mark current class as promoted
            $ks->update(['status' => 'promoted', 'tanggal_lulus' => now()]);

            // Assign new class
            KelasSiswa::create([
                'siswa_id' => $siswa->id_siswa,
                'ruang_kelas_id' => $ruangBaru->id_ruang_kelas,
                'status' => 'active',
                'tanggal_masuk' => now(),
            ]);

            $totalNaik++;
        }

        $this->info("✅ Class promotion process completed.");
        $this->info("🎓 Graduated students: {$totalLulus}");
        $this->info("📚 Promoted students: {$totalNaik}");
    }
}
