<?php

/**
 *
 * PHP version  5.3
 * @author      Liviu Panainte <liviu.panainte at gmail.com>
 */

namespace Glue\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Glue\Application;

class Controller
{

    protected $app;
    protected $redirect_url;
    
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->redirect_url = null;
    }
    
    public function generateUrl($route, $options = array()) {
        return $this->app->shared['url.generator']->generate($route, $options);
    }
    
    public function setRedirectUrl($redirect_url) {
        $this->redirect_url = $redirect_url;
    }
    
    public function getRedirectUrl() {
        return $this->redirect_url;
    }
    
    /**
     * Redirects the user to another URL.
     *
     * @param string  $url    The URL to redirect to
     * @param integer $status The status code (302 by default)
     *
     * @return RedirectResponse
     */
    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    public function render($view, array $parameters = array())
    {
        $app = $this->app;
        $parameters += compact('app');
        $template = $this->app->getProvider('twig')->render($this->getClassShortName() . '/' . $view, $parameters);
        $response = new Response();
        $response->setContent($template);

        return $response;
    }

    public function getClassShortName()
    {
        return preg_replace('/.*\\\|Controller/i', '', get_class($this));
    }

}