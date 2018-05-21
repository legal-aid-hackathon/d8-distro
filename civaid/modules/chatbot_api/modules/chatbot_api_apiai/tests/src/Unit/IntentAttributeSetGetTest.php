<?php

namespace Drupal\Tests\chatbot_api_apiai\Unit;

use Drupal\api_ai_webhook\ApiAi\Model\Webhook\Request;
use Drupal\api_ai_webhook\ApiAi\Model\Webhook\Response;
use Drupal\chatbot_api_apiai\IntentRequestApiAiProxy;
use Drupal\chatbot_api_apiai\IntentResponseApiAiProxy;
use Drupal\Tests\UnitTestCase;

/**
 * Tests set/get intent attributes for Api.AI proxy classes.
 *
 * @group chatbot_api
 */
class IntentAttributeSetGetTest extends UnitTestCase {

  /**
   * Tests getIntentAttribute() method.
   */
  public function testGetIntentAttribute() {
    $request_data = [
      'timestamp' => '2017-02-09T16:06:01.908Z',
      'result' => [
        'contexts' => [
          [
            'name' => 'weather',
            'lifespan' => 2,
            'parameters' => [
              'city' => 'Rome',
              'day' => 'Monday',
            ],
          ],
          [
            'name' => 'persona',
            'lifespan' => 2,
            'parameters' => [
              'name' => 'Marie',
              'gender' => 'female',
            ],
          ],
        ],
      ],
    ];
    $original_request = new Request($request_data);
    $request = new IntentRequestApiAiProxy($original_request);

    $this->assertEquals('Rome', $request->getIntentAttribute('weather.city'));
    $this->assertEquals('Monday', $request->getIntentAttribute('weather.day'));
    $this->assertEquals('Marie', $request->getIntentAttribute('persona.name'));
    $this->assertEquals('female', $request->getIntentAttribute('persona.gender'));
  }

  /**
   * Tests setIntentAttribute() method.
   */
  public function testSetIntentAttribute() {

    $original_response = new Response();
    $response = new IntentResponseApiAiProxy($original_response);

    // Set some contexts.
    $response->addIntentAttribute('weather.city', 'Rome');
    $response->addIntentAttribute('weather.day', 'Monday');
    $response->addIntentAttribute('persona.name', 'Marie');
    $response->addIntentAttribute('persona.gender', 'female');
    $likes = ['sea', 'food', 'drupal'];
    $response->addIntentAttribute('persona.likes', $likes);

    // Assert setter works.
    $data = $response->jsonSerialize();
    $this->assertArrayHasKey('contextOut', $data);
    $this->assertEquals($data['contextOut'][0]->getName(), 'weather');
    $this->assertEquals($data['contextOut'][0]->getParameters()['city'], 'Rome');
    $this->assertEquals($data['contextOut'][0]->getParameters()['day'], 'Monday');
    $this->assertEquals($data['contextOut'][1]->getName(), 'persona');
    $this->assertEquals($data['contextOut'][1]->getParameters()['name'], 'Marie');
    $this->assertEquals($data['contextOut'][1]->getParameters()['gender'], 'female');
    $this->assertEquals($data['contextOut'][1]->getParameters()['likes'], $likes);

    // Change some parameters. Change the value type too, to make sure
    // overriding the value type is allowed.
    $response->addIntentAttribute('weather.day', ['Monday', 'Thursday']);
    $response->addIntentAttribute('persona.likes', 'all the small things');

    // Assert setter works with changing existing values and their types.
    $data = $response->jsonSerialize();
    $this->assertEquals($data['contextOut'][0]->getParameters()['day'], ['Monday', 'Thursday']);
    $this->assertEquals($data['contextOut'][1]->getParameters()['likes'], 'all the small things');

    // Also make sure previous parameters are unaltered.
    $this->assertEquals($data['contextOut'][0]->getParameters()['city'], 'Rome');
    $this->assertEquals($data['contextOut'][1]->getParameters()['gender'], 'female');
  }

}
