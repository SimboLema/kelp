<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="side-menu">
        <a href="{{ route('businessOwner.dashboard') }}">Dashbord</a>
        <a href="{{ route('business.reviews') }}">Reviews</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Comment</th>
                <th>Rating</th>
                <th>Reply</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($reviews as $review)
            <tr>
                <td>{{ $review->user_name }}</td>
                <td>{{ $review->comment }}</td>
                <td>{{ $review->rating }}</td>

                <td>
                    @if($review->reply)
                        <strong>{{ $review->reply->reply }}</strong>
                    @else
                        <form action="{{ route('business.review.reply', $review->id) }}" method="POST">
                            @csrf

                            <textarea
                                name="reply"
                                rows="3"
                                placeholder="Write your reply..."
                                required></textarea>

                            <button type="submit">Reply</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
