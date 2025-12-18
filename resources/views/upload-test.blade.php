<!DOCTYPE html>
<html>
<head>
    <title>Test Upload</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .result { margin-top: 20px; padding: 15px; border: 1px solid #ccc; }
        .success { background: #d4edda; border-color: #c3e6cb; }
        .error { background: #f8d7da; border-color: #f5c6cb; }
    </style>
</head>
<body>
    <h1>Test Upload File</h1>
    <form method="POST" action="/upload-test" enctype="multipart/form-data">
        @csrf
        <input type="file" name="test_image" required>
        <button type="submit">Upload Test</button>
    </form>

    @if(isset($results))
        <div class="result">
            <h3>Upload Results:</h3>
            @foreach($results as $name => $result)
                <div class="{{ $result['success'] ? 'success' : 'error' }}">
                    <strong>{{ $name }}:</strong>
                    @if($result['success'])
                        ✓ Success<br>
                        Path: {{ $result['path'] }}<br>
                        Exists: {{ $result['exists'] ? 'YES' : 'NO' }}<br>
                        @if(isset($result['url']))
                            URL: <a href="{{ $result['url'] }}" target="_blank">{{ $result['url'] }}</a>
                        @endif
                    @else
                        ✗ Error: {{ $result['error'] }}
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <hr>

    <h3>Test Storage Link:</h3>
    <a href="/storage-test">Test Storage Link</a>
</body>
</html>
