<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game of Life</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            margin-top: 20px;
        }
        table td {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 24px;
        }
    </style>
</head>
<body>
<h1>Game of Life</h1>

@if(!isset($grid))
    <form action="/load-file" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="generation_file">Upload a generation file:</label>
        <input type="file" name="generation_file" required>
        <button type="submit">Load File</button>
    </form>
@else
    <p>Generation {{ $generation }}</p>
    <p>Grid Size: {{ $gridSize['rows'] }} x {{ $gridSize['cols'] }}</p>

    <table>
        @foreach($grid as $row)
            <tr>
                @foreach($row as $cell)
                    <td>{{ $cell === '*' ? '🌳' : '🌑' }}</td>
                @endforeach
            </tr>
        @endforeach
    </table>

    <form action="/next-generation" method="POST">
        @csrf
        <input type="hidden" name="generation" value="{{ $generation }}">
        <input type="hidden" name="gridSize" value="{{ json_encode($gridSize) }}">
        <input type="hidden" name="grid" value="{{ json_encode($grid) }}">
        <button type="submit">Calculate Next Generation</button>
    </form>
@endif
</body>
</html>
