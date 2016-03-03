<!DOCTYPE html>
<html>
    <head>
        <title>Laravel Drydock</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        @if(config('app.debug'))
        <script src="/jspm_packages/system.js"></script>
        <script src="/jspm.browser.js"></script>
        <script src="/jspm.config.js"></script>
        <script>
            System.import('lib/welcome.ts');
        </script>
        @endif

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

            .notice {
                margin-top: 10px;
                font-weight: 800;
                color: #005AA0;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">

                <div class="title">Laravel Drydock</div>

                <a href="/dev/info">Environment Information</a>
                <a href="http://{{ Request::getHost() }}:8081">RabbitMQ</a>
                <a href="http://{{ Request::getHost() }}:8082">E-Mail</a>
                <a href="http://github.com/atrauzzi/laravel-drydock">Github</a>

                @if($lastCronRun)
                    <div class="notice">Last cron run {{ $lastCronRun }} (runs every minute)</div>
                @endif

                <hr />

                <input type="email" id="email" placeholder="Enter an email address..." />
                <input type="text" id="message" placeholder="Enter a message..." />
                <a href="javascript:void(0)" onclick="location.href='/dev/queue/test?message=' + message.value + '&email=' + email.value; message.value=''; email.value='';">Queue it!</a>

                <br />

                <?php $message = Cache::pull('last-message') ?>
                <div id="last-message">
                    @if($message)
                    <div>
                        <h4>Your message is done processing!</h4>
                        <div class="notice">{{ $message }}</div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </body>
</html>
