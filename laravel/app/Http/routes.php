<?php use Illuminate\Routing\Router;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Jobs\TestJob;

/** @var \Illuminate\Routing\Router $route */
$route = app('Illuminate\Routing\Router');

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

$route->group(['middleware' => ['web']], function (Router $route) {

	$route->get('/', function () {

		$lastCronRun = new \Carbon\Carbon(Cache::get('last-cron'));
		$lastCronRun = $lastCronRun->diffForHumans();

		return view('welcome', compact('lastCronRun'));

	});

	$route->get('/dev/info', function () {
		ob_start();
		phpinfo();
		$phpinfo = ob_get_contents();
		ob_end_clean();
		return new Response($phpinfo);
	});

	$route->get('/dev/queue/test', function (Request $request) {
		dispatch(new TestJob($request->get('message'), $request->get('email')));
		return new Response(null, 204);
	});

	$route->get('/api/web/last-message', function (Request $request) {
		return new JsonResponse(Cache::pull('last-message'));
	});

});
