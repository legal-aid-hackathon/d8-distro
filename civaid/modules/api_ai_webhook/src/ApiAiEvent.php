<?php

namespace Drupal\api_ai_webhook;

use Drupal\api_ai_webhook\ApiAi\Model\Webhook\Request;
use Drupal\api_ai_webhook\ApiAi\Model\Webhook\Response;
use Symfony\Component\EventDispatcher\Event;

/**
 * Implements a new Symfony event.
 *
 * This class implements a new Symfony event called ApiAIEvent which will be
 * dispatched when a new request comes in through the exposed webhook.
 */
class ApiAiEvent extends Event {

  const NAME = 'api_ai_webhook_event.request';

  /**
   * The webhook request.
   *
   * @var \Drupal\api_ai_webhook\ApiAi\Model\Webhook\Request
   */
  protected $request;

  /**
   * The response object.
   *
   * @var \Drupal\api_ai_webhook\ApiAi\Model\Webhook\Response
   */
  protected $response;

  /**
   * Constructor.
   *
   * @param \Drupal\api_ai_webhook\ApiAi\Model\Webhook\Request $request
   *   The request.
   * @param \Drupal\api_ai_webhook\ApiAi\Model\Webhook\Response $response
   *   An Alexa response object to use for any response.
   */
  public function __construct(Request $request, Response $response) {
    $this->request = $request;
    $this->response = $response;
  }

  /**
   * Getter for the request object.
   *
   * @return \Drupal\api_ai_webhook\ApiAi\Model\Webhook\Request
   *   The associated webhook request.
   */
  public function getRequest() {
    return $this->request;
  }

  /**
   * Setter for the request object.
   *
   * @param \Drupal\api_ai_webhook\ApiAi\Model\Webhook\Request $request
   *   The webhook request to associate with this event.
   */
  public function setRequest(Request $request) {
    $this->request = $request;
  }

  /**
   * Getter for the response object.
   *
   * @return \Drupal\api_ai_webhook\ApiAi\Model\Webhook\Response
   *   The associated response.
   */
  public function getResponse() {
    return $this->response;
  }

  /**
   * Setter for the response object.
   *
   * @param \Drupal\api_ai_webhook\ApiAi\Model\Webhook\Response $response
   *   The response to associate with this event.
   */
  public function setResponse(Response $response) {
    $this->response = $response;
  }

}
