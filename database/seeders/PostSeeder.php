<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Province;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->pluck('id')->toArray();
        $provinces = Province::pluck('id')->toArray();
        $statuses = ['pending', 'in_progress', 'resolved'];

        $titles = [
            'Jalan Rusak di Kawasan Pemukiman',
            'Lampu Jalan Padam di Jalan Utama',
            'Banjir Rutin di Perumahan',
            'Fasilitas Kesehatan Kurang Memadai',
            'Pelayanan Pendidikan Belum Optimal',
            'Sampah Menumpuk di Pasar Tradisional',
            'Krisis Air Bersih di Musim Kemarau',
            'Perbaikan Jembatan yang Rusak',
            'Layanan Transportasi Umum Terbatas',
            'Trotoar Rusak dan Tidak Aman',
            'Keamanan Lingkungan Menurun',
            'Saluran Drainase Tersumbat',
            'Pencemaran Sungai oleh Limbah',
            'Kebisingan dari Pabrik Terdekat',
            'Penebangan Liar di Hutan Lindung'
        ];

        $contents = [
            "Saya ingin melaporkan kondisi jalan di lingkungan RT 003/RW 002 yang rusak parah. Banyak lubang besar yang membahayakan pengendara, terutama saat hujan karena tergenang air. Sudah beberapa kali terjadi kecelakaan akibat jalan ini. Mohon segera diperbaiki untuk keselamatan warga.",
            
            "Dengan hormat, saya melaporkan bahwa saluran air di perumahan kami tersumbat dan menyebabkan banjir setiap hujan deras. Air masuk ke rumah warga setinggi 30-50 cm. Kondisi ini sudah berlangsung selama 3 bulan terakhir. Mohon tindakan segera untuk normalisasi saluran air.",
            
            "Saya ingin melaporkan bahwa layanan kesehatan di Puskesmas Sejahtera sangat lambat dan tidak teratur. Pasien harus menunggu berjam-jam, bahkan dalam kondisi darurat. Selain itu, stok obat sering kosong. Mohon perhatian untuk perbaikan layanan kesehatan dasar ini.",
            
            "Dengan ini saya melaporkan bahwa sampah di Pasar Rakyat tidak diangkut selama seminggu terakhir. Tumpukan sampah menimbulkan bau tidak sedap dan berpotensi menyebarkan penyakit. Para pedagang dan pembeli sangat terganggu dengan kondisi ini.",
            
            "Saya melaporkan adanya pencemaran air sungai di belakang kawasan industri. Air berubah warna menjadi hitam dan berbau kimia. Warga yang biasa menggunakan air sungai untuk kebutuhan sehari-hari kini kesulitan mendapatkan air bersih. Mohon ditindaklanjuti.",
            
            "Perlu saya laporkan bahwa terdapat kerusakan pada Jembatan Harapan yang menghubungkan Desa Sukamaju dan Desa Suka Makmur. Beberapa bagian jembatan mulai rapuh dan membahayakan pengguna. Jembatan ini adalah akses utama bagi warga kedua desa.",
            
            "Mohon perhatian terhadap kondisi sekolah dasar di desa kami yang memprihatinkan. Atap bocor, beberapa kelas tidak memiliki kursi yang cukup, dan fasilitas toilet sangat buruk. Anak-anak berhak mendapatkan lingkungan belajar yang layak.",
            
            "Saya ingin melaporkan bahwa layanan administrasi di kantor kelurahan sangat lambat dan berbelit. Untuk mengurus surat sederhana saja bisa memakan waktu berhari-hari. Beberapa oknum petugas juga meminta bayaran tambahan. Mohon ditindaklanjuti demi pelayanan publik yang lebih baik.",
            
            "Dengan ini saya laporkan kondisi trotoar di sepanjang Jalan Utama yang rusak dan banyak berlubang. Pejalan kaki, terutama lansia dan penyandang disabilitas, kesulitan menggunakan trotoar ini. Beberapa bagian juga digunakan untuk parkir liar sehingga memaksa pejalan kaki turun ke jalan raya.",
            
            "Saya melaporkan gangguan keamanan yang meningkat di lingkungan kami. Dalam sebulan terakhir, sudah terjadi 5 kasus pencurian dan 3 kasus perampokan. Patroli keamanan sangat jarang terlihat. Warga merasa tidak aman dan khawatir.",
            
            "Mohon perhatian terhadap pelayanan air bersih yang sering mati tanpa pemberitahuan. Dalam seminggu bisa 2-3 kali air tidak mengalir. Ini sangat mengganggu aktivitas sehari-hari warga. Ketika ditanyakan ke petugas, tidak ada jawaban yang memuaskan.",
            
            "Saya ingin melaporkan kondisi taman kota yang tidak terawat. Rumput tinggi, banyak sampah berserakan, dan fasilitas bermain anak rusak. Taman yang seharusnya menjadi tempat rekreasi keluarga kini tidak layak dikunjungi.",
            
            "Dengan hormat, saya melaporkan kondisi jalan Raya Sukamaju yang gelap gulita di malam hari karena lampu jalan tidak berfungsi sejak 2 bulan lalu. Kondisi ini sangat berbahaya bagi pengendara dan pejalan kaki. Sudah terjadi beberapa kasus perampokan di area ini.",
            
            "Saya ingin melaporkan penebangan liar yang terjadi di kawasan hutan lindung dekat desa kami. Aktivitas ini berlangsung terutama di malam hari. Selain merusak lingkungan, hal ini juga menyebabkan banjir bandang saat hujan deras.",
            
            "Mohon perhatian terhadap kondisi jalan dan drainase di Perumahan Indah Permai. Setiap hujan, air tergenang hingga masuk ke rumah warga. Drainase tersumbat dan tidak pernah dibersihkan selama bertahun-tahun. Kondisi jalan juga rusak parah akibat genangan air."
        ];
        
        $statusNotes = [
            'in_progress' => [
                'Tim kami sedang mensurvei lokasi dan akan segera mengambil tindakan.',
                'Pengaduan telah diteruskan ke dinas terkait untuk ditindaklanjuti.',
                'Sedang dalam proses koordinasi dengan pihak berwenang setempat.',
                'Tim teknis sudah diturunkan untuk mengatasi masalah tersebut.',
                'Telah dijadwalkan untuk penanganan minggu ini.'
            ],
            'resolved' => [
                'Perbaikan telah dilakukan dan masalah sudah teratasi sepenuhnya.',
                'Pengaduan telah ditindaklanjuti dan diselesaikan oleh tim kami.',
                'Masalah sudah teratasi, terima kasih atas laporannya.',
                'Perbaikan selesai dilakukan pada tanggal 10 April 2025.',
                'Penanganan telah selesai, silakan konfirmasi jika masih ada kendala.'
            ]
        ];

        for ($i = 0; $i < 9; $i++) {
            $status = $statuses[array_rand($statuses)];
            $statusNote = null;

            if ($status === 'in_progress') {
                $statusNote = $statusNotes['in_progress'][array_rand($statusNotes['in_progress'])];
            } else if ($status === 'resolved') {
                $statusNote = $statusNotes['resolved'][array_rand($statusNotes['resolved'])];
            }

            Post::create([
                'user_id' => $users[array_rand($users)],
                'province_id' => $provinces[array_rand($provinces)],
                'title' => $titles[array_rand($titles)],
                'content' => $contents[array_rand($contents)],
                'status' => $status,
                'views' => $views = fake()->numberBetween(0, 500),
                'likes' => fake()->numberBetween(0, $views),
                'image_path' => 'https://picsum.photos/600/300',
                'status_note' => $statusNote,
                'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }
    }
}