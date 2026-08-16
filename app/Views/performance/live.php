<!DOCTYPE html>
<html>
<head>
    <title>SSE Test</title>
</head>
<body>

<h1>SSE Test</h1>

<pre id="output"></pre>

<script>

    const output = document.getElementById('output');

    const source = new EventSource(
        '/api/performance/session/1/stream'
    );

    source.addEventListener('coach.dashboard', function(e) {

        console.log('Evento recibido');

        const data = JSON.parse(e.data);

        output.textContent =
            JSON.stringify(data, null, 2);
    });

    source.onerror = function(err) {
        console.error(err);
    };

</script>

</body>
</html>