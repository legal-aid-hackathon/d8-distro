<?php

namespace Drupal\chatbot_api_alexa\EventSubscriber;

use Drupal\alexa\AlexaEvent;
use Drupal\chatbot_api_alexa\IntentRequestAlexaProxy;
use Drupal\chatbot_api_alexa\IntentResponseAlexaProxy;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An event subscriber for Alexa request events.
 */
class RequestSubscriber implements EventSubscriberInterface {

  /**
   * Gets the event.
   */
  public static function getSubscribedEvents() {
    $events['alexaevent.request'][] = ['onRequest', 0];
    return $events;
  }

  /**
   * Called upon a request event.
   *
   * @param \Drupal\alexa\AlexaEvent $event
   *   The event object.
   */
  public function onRequest(AlexaEvent $event) {
    /** @var \Drupal\chatbot_api_alexa\IntentRequestAlexaProxy|\Alexa\Request\IntentRequest $request */
    $request = new IntentRequestAlexaProxy($event->getRequest());
    $response = new IntentResponseAlexaProxy($event->getResponse());

    /** @var \Drupal\chatbot_api\Plugin\IntentPluginManager $manager */
    $manager = \Drupal::service('plugin.manager.chatbot_intent_plugin');
    if ($manager->hasDefinition($request->getIntentName())) {

      $configuration = [
        'request' => $request,
        'response' => $response,
      ];
      $plugin = $manager->createInstance($request->getIntentName(), $configuration);
      $plugin->process();
    }
  }

}
