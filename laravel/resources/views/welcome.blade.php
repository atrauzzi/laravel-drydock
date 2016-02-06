<!DOCTYPE html>
<html>
    <head>
        <title>Laravel 5</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
                font-weight: normal;
            }

            a:link, a:visited {
                text-decoration: none;
                color: #002585;
                font-weight: bold;
                margin-left: 5px;
                margin-right: 5px;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">

                <div class="title">Laravel Drydock</div>

                <a href="/dev/info">Environment Information</a>
                <a href="http://{{ Request::getHost() }}:8081">RabbitMQ</a>
                <a href="http://github.com/atrauzzi/laravel-drydock">Github</a>

                <hr />

                <input type="text" id="message" placeholder="Enter a message..." />
                <a href="javascript:void(0)" onclick="location.href='/dev/queue/test?message=' + message.value; message.value='';">Queue it!</a>

                <br />

                @if($message = Cache::pull('last-message'))
                    <h3>Your message is done processing!</h3>
                    <div>{{ $message }}</div>
                @endif

            </div>
        </div>
    </body>
</html>
