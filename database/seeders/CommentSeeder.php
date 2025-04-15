<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = Post::all();
        $users = User::all();
        
        $realComments = [
            'Terima kasih atas tanggapan cepatnya!',
            'Saya juga mengalami masalah yang sama di daerah saya.',
            'Sudah berapa lama masalah ini terjadi?',
            'Mohon ditindaklanjuti dengan segera.',
            'Alhamdulillah sudah ada perbaikan di daerah kami.',
            'Kapan kira-kira akan selesai?',
            'Saya setuju dengan laporan ini, kondisinya memang memprihatinkan.',
            'Tolong dijelaskan prosedur pengaduannya seperti apa?',
            'Daerah kami juga butuh perhatian untuk masalah serupa.',
            'Sudah 3 bulan belum ada tindak lanjut.',
            'Bagaimana cara mengajukan pengaduan resmi?',
            'Mohon bantuannya, ini sangat mendesak.',
            'Semoga cepat teratasi masalahnya.',
            'Saya sudah melaporkan ini sejak tahun lalu.',
            'Apakah ada nomor telepon yang bisa dihubungi untuk info lebih lanjut?'
        ];

        // add 0-5 random comments
        foreach ($posts as $post) {
            $commentCount = rand(0, 5);

            for ($i = 0; $i < $commentCount; $i++) {
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                    'comment' => $realComments[array_rand($realComments)],
                    'created_at' => fake()->dateTimeBetween($post->created_at, 'now')
                ]);
            }
        }
    }
}