<?php

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;

class AppCache extends HttpCache
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param bool $catch
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    protected function invalidate(\Symfony\Component\HttpFoundation\Request $request, $catch = false)
    {
        if ('DELETE' !== $request->getMethod()) {
            return parent::invalidate($request, $catch);
        }
        if ('192.168.99.100' !== $request->getClientIp()) {
            return new \Symfony\Component\HttpFoundation\Response(
                'Invalid http method',
                \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST
            );
        }
        $response = new \Symfony\Component\HttpFoundation\Response();
        if ($this->getStore()->purge($request->getUri())) {
            $response->setStatusCode(\Symfony\Component\HttpFoundation\Response::HTTP_OK, 'Purged');
        } else {
            $response->setStatusCode(\Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND, 'Not found');
        }

        return $response;
    }
}
