<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            if ($request->isAJAX()) {
                return response()->setJSON([
                    'status'   => 'error',
                    'message'  => 'Session expired or unauthenticated. Please log in.',
                    'redirect' => site_url('login')
                ])->setStatusCode(401);
            }

            return redirect()->to('login');
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
