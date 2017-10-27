<div class="blog-view">
    <table id="" class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <th>Title</th>
                <td>{{ $post->title }}</td>
            </tr>
            <tr>
                <th>Url</th>
                <td><a href="{{ $post->url }}" target="_blank">{{ $post->url }}</a></td>
            </tr>
            <tr>
                <th>Description</th>
                <td>{{ $post->description }}</td>
            </tr>
            <tr>
                <th>Total Up</th>
                <td>{{ $post->total_up_votes }}</td>
            </tr>
            <tr>
                <th>Total Down</th>
                <td>{{ $post->total_down_votes }}</td>
            </tr>
        </tbody>
    </table>
    
    @if(!empty($post->userPost)) 
        <p><b>Vote History:</b></p>
        @foreach($post->userPost as $voters)
            <p>
                {{ $voters->voterprofile->name }}: 
                {!! ($voters->vote_status == 1) ? '<span class="glyphicon glyphicon-thumbs-up"></span>' : '<span class="glyphicon glyphicon-thumbs-down"></span>' !!}

            </p>
        @endforeach
    @endif
</div>