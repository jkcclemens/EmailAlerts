<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\Util\StringUtils;

class VerifyCsrfToken {

    /**
     * The encrypter implementation.
     *
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;
    private $excludes = [
        'receive'
    ];

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Encryption\Encrypter $encrypter
     */
    public function __construct(Encrypter $encrypter) {
        $this->encrypter = $encrypter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next) {
        $excluded = false;
        foreach ($this->excludes as $exclude) {
            if ($request->is($exclude)) {
                $excluded = true;
                break;
            }
        }
        if ($excluded || $this->isReading($request) || $this->tokensMatch($request)) {
            return $this->addCookieToResponse($request, $next($request));
        }

        throw new TokenMismatchException;
    }

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    protected function tokensMatch($request) {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        if (!$token && $header = $request->header('X-XSRF-TOKEN')) {
            $token = $this->encrypter->decrypt($header);
        }

        return StringUtils::equals($request->session()->token(), $token);
    }

    /**
     * Add the CSRF token to the response cookies.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Http\Response $response
     * @return \Illuminate\Http\Response
     */
    protected function addCookieToResponse($request, $response) {
        $response->headers->setCookie(
            new Cookie('XSRF-TOKEN', $request->session()->token(), time() + 60 * 120, '/', null, false, false)
        );

        return $response;
    }

    /**
     * Determine if the HTTP request uses a ‘read’ verb.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    protected function isReading($request) {
        return in_array($request->method(), ['HEAD', 'GET', 'OPTIONS']);
    }
}
