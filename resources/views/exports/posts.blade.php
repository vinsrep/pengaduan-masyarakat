<table>
    <thead>
        <tr>
            <th colspan="10" style="text-align: center; font-weight: bold;">Daftar laporan Pengaduan Masyarakat</th>
        </tr>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Author</th>
            <th>Province</th>
            <th>Status</th>
            <th>Views</th>
            <th>Likes</th>
            <th>Created At</th>
            <th>Updated At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->content }}</td>
                <td>{{ $post->user->name ?? 'Unknown' }}</td>
                <td>{{ $post->province->name ?? 'Unknown' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $post->status)) }}</td>
                <td>{{ $post->views }}</td>
                <td>{{ $post->likes }}</td>
                <td>{{ $post->created_at }}</td>
                <td>{{ $post->updated_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
