<?php

namespace App\Exports;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PostExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $posts;

    public function __construct($posts)
    {
        // get collection if paginator
        if (method_exists($posts, 'getCollection')) {
            $this->posts = $posts->getCollection();
        } else {
            $this->posts = $posts;
        }
    }

    public function view(): View
    {
        return view('exports.posts', [
            'posts' => $this->posts
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
            ],
        ];
    }
}
